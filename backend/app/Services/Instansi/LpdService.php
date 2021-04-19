<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Akun;
use App\Models\Instansi;
use App\Models\MBerkasLpdPaud;
use App\Models\MGroup;
use App\Models\MJenisInstansi;
use App\Models\MVervalPaud;
use App\Models\PaudAdmin;
use App\Models\PaudInstansi;
use App\Models\PaudInstansiBerkas;
use App\Services\InstansiService;
use Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LpdService
{
    /**
     * @param array $params
     * @return Builder
     */
    public function query($params = [])
    {
        $query = PaudInstansi::query()
            ->join('instansi', 'paud_instansi.instansi_id', '=', 'instansi.instansi_id')
            ->select(['paud_instansi.*'])
            ->where([
                'instansi.k_jenis_instansi' => MJenisInstansi::LPD_PAUD,
                'paud_instansi.tahun'       => $params['tahun'] ?? config('paud.tahun'),
                'paud_instansi.angkatan'    => $params['angkatan'] ?? config('paud.angkatan'),
            ])
            ->with([
                'instansi.mPropinsi',
                'instansi.mKota',
            ]);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where(function ($query) use ($keyword) {
                $query->where('instansi.nama', 'like', '%' . $keyword . '%');
            });
        }

        return $query;
    }

    /**
     * @param array $data
     * @return PaudInstansi
     */
    public function create(array $data)
    {
        $prefix  = 72;
        $maxKode = (int)"{$prefix}9999";
        do {
            $instansiId = Instansi::where('instansi_id', 'like', "{$prefix}%")->max('instansi_id') ?: (int)"{$prefix}0000";
            $instansiId++;

            if ($instansiId <= $maxKode) {
                break;
            }

            $prefix++;
            $maxKode = (int)"{$prefix}9999";
        } while (true);

        $instansi     = new Instansi($data);
        $paudInstansi = new PaudInstansi($data);

        $instansi->instansi_id      = $instansiId;
        $instansi->k_jenis_instansi = MJenisInstansi::LPD_PAUD;

        $paudInstansi->instansi_id   = $instansiId;
        $paudInstansi->tahun         = config('paud.tahun');
        $paudInstansi->angkatan      = config('paud.angkatan');
        $paudInstansi->k_verval_paud = MVervalPaud::KANDIDAT;

        $instansi->save();
        $paudInstansi->save();

        return $paudInstansi;
    }

    /**
     * @param PaudInstansi $paudInstansi
     * @return PaudInstansi
     */
    public function fetch(PaudInstansi $paudInstansi)
    {
        $paudInstansi->instansi
            ->load([
                'mPropinsi',
                'mKota',
            ]);

        return $paudInstansi;
    }

    public function getOperatorLpd(Akun $akun, Instansi $instansi)
    {
        $operator = PaudAdmin::whereAkunId($akun->akun_id)
            ->where('instansi_id', '=', $instansi->instansi_id)
            ->where('k_group', MGroup::OP_LPD_DIKLAT_PAUD)->first();

        if(!$operator) {
            throw new FlowException('Operator tidak terdaftar di instansi '.$instansi->nama);
        }

        return $operator;
    }

    public function getPaudInstansi(Instansi $instansi, PaudAdmin $paudAdmin)
    {
        if ($paudAdmin->instansi_id != $instansi->instansi_id) {
            abort(404);;
        }

        return PaudInstansi::whereInstansiId($instansi->instansi_id)
            ->where('angkatan', config('paud.angkatan'))
            ->where('tahun', config('paud.tahun'))->first();
    }

    public function getStatusLengkap(PaudInstansi $paudInstansi)
    {
        $instansi = $paudInstansi->instansi;

        $diklat = ($paudInstansi->diklat) ? json_decode($paudInstansi->diklat, true) : [];

        $isLengkapProfil = $instansi->nama && $instansi->no_telpon && $instansi->email && $instansi->alamat &&
            $instansi->k_propinsi && $instansi->k_kota && $paudInstansi->nama_penanggung_jawab &&
            $paudInstansi->nama_sekretaris && $paudInstansi->nama_bendahara && $paudInstansi->telp_penanggung_jawab &&
            $paudInstansi->telp_sekretaris && $paudInstansi->telp_bendahara && (count($diklat) >= 1);

        $isLengkapBerkas = $paudInstansi->paudInstansiBerkases->count() == 7;

        return [
            'profil' => $isLengkapProfil,
            'berkas' => $isLengkapBerkas,
        ];
    }

    public function upload(PaudInstansi $paudInstansi, int $kBerkasLpdPaud, UploadedFile $file)
    {
        $mBerkasLpdPaud = MBerkasLpdPaud::findOrFail($kBerkasLpdPaud);
        $deleteBerkas   = null;

        $berkases = $paudInstansi->paudInstansiBerkases()->where('k_berkas_lpd_paud', $kBerkasLpdPaud)->get();
        if ($mBerkasLpdPaud->max == 1 && $berkases->count()) {
            /** @var PaudInstansiBerkas $berkas */
            $berkas       = $berkases->first();
            $deleteBerkas = $berkas->getOriginal('file');
        } else {
            $berkas = new PaudInstansiBerkas();
            $berkas->fill($paudInstansi->toArray());
            $berkas->k_berkas_lpd_paud = $kBerkasLpdPaud;
        }

        $berkas->nama = $file->getClientOriginalName();
        $berkas->file = $this->uploadFtp($paudInstansi, $mBerkasLpdPaud->singkat, $file);
        if (!$berkas->save()) {
            throw new FlowException('Berkas gagal diupload. silahkan ulangi lagi');
        }

        if ($deleteBerkas) {
            $this->deleteFtp($deleteBerkas);
        }

        return $berkas;
    }

    private function uploadFtp(PaudInstansi $paudInstansi, $label, $file)
    {
        $ftpPath  = config('filesystems.disks.lpd-berkas.path');
        $ext      = $file->guessExtension();
        $filename = "lpd-{$paudInstansi->instansi_id}/" . implode('-', [$label, $paudInstansi->instansi_id, date('ymdhis')]) . ".$ext";
        $path     = sprintf("%s/%s", $ftpPath, $filename);

        if (!Storage::disk('lpd-berkas')->put($path, file_get_contents($file->getRealPath()))) {
            throw new FlowException("Unggah Berkas $label tidak berhasil");
        }

        return $filename;
    }

    public function deleteFtp($filename)
    {
        $ftpPath = config('filesystems.disks.lpd-berkas.path');
        $delete  = sprintf("%s/%s", $ftpPath, $filename);
        return Storage::disk('lpd-berkas')->delete($delete);
    }

    public function update(PaudInstansi $paudInstansi, array $data, string $foto, string $ext)
    {
        $instansi = $paudInstansi->instansi;

        $oldFoto = $instansi->foto ? $instansi->getOriginal('foto') : null;

        $paudInstansi->fill(Arr::except($data, 'diklat'));
        if ($diklat = Arr::get($data, 'diklat')) {
            $paudInstansi->diklat = json_encode($diklat);
        }
        if (!$paudInstansi->save()) {
            throw new SaveException("Penyimpanan Data Lembaga Tidak Berhasil");
        }

        $instansi->fill(Arr::only($data, [
            'nama',
            'email',
            'alamat',
            'no_telp',
            'k_propinsi',
            'k_kota',
            'kodepos',
        ]));

        if ($foto && $ext) {
            $instansi->foto = app(InstansiService::class)->uploadFoto($instansi, $foto, $ext);
        }

        if (!$instansi->save()) {
            throw new SaveException("Penyimpanan Data Lembaga Tidak Berhasil");
        }
        if ($foto && $ext && $oldFoto) {
            app(InstansiService::class)->deleteFoto($oldFoto);
        }
    }
}
