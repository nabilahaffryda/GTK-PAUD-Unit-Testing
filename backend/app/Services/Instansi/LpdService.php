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
use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Carbon\Carbon;
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
            ->when(isset($params['is_aktif']), function (Builder $query) use ($params) {
                $query->where('paud_instansi.is_aktif', $params['is_aktif']);
            })
            ->when(isset($params['k_kota']), function (Builder $query) use ($params) {
                $query->where('instansi.k_kota', $params['k_kota']);
            })
            ->when(isset($params['k_propinsi']), function (Builder $query) use ($params) {
                $query->where('instansi.k_propinsi', $params['k_propinsi']);
            })
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

    /**
     * @return PaudInstansi
     */
    public function update(PaudInstansi $paudInstansi, array $data)
    {
        $instansi = $paudInstansi->instansi;

        $instansi->nama = $data['nama'] ?? $instansi->nama;

        $instansi->save();

        return $paudInstansi;
    }

    public function getOperatorLpd(Akun $akun, Instansi $instansi)
    {
        $operator = PaudAdmin::whereAkunId($akun->akun_id)
            ->where('instansi_id', '=', $instansi->instansi_id)
            ->where('k_group', MGroup::OP_LPD_DIKLAT_PAUD)->first();

        if (!$operator) {
            throw new FlowException('Operator tidak terdaftar di instansi ' . $instansi->nama);
        }

        return $operator;
    }

    public function getPaudInstansi(Instansi $instansi, PaudAdmin $paudAdmin)
    {
        if ($paudAdmin->instansi_id != $instansi->instansi_id) {
            abort(404);
        }

        return PaudInstansi::whereInstansiId($instansi->instansi_id)
            ->where('angkatan', config('paud.angkatan'))
            ->where('tahun', config('paud.tahun'))
            ->with(['instansi.mKota', 'instansi.mPropinsi'])->first();
    }

    public function getStatusLengkap(PaudInstansi $paudInstansi)
    {
        $instansi = $paudInstansi->instansi;

        $isLengkapProfil = $instansi->nama && $instansi->no_telpon && $instansi->email && $instansi->alamat &&
            $instansi->k_propinsi && $instansi->k_kota && $paudInstansi->nama_penanggung_jawab &&
            $paudInstansi->nama_sekretaris && $paudInstansi->nama_bendahara && $paudInstansi->telp_penanggung_jawab &&
            $paudInstansi->telp_sekretaris && $paudInstansi->telp_bendahara && $paudInstansi->diklat &&
            is_array($paudInstansi->diklat) && (count($paudInstansi->diklat) >= 1);

        $kBerkases = [
            MBerkasLpdPaud::AKTA_LEMBAGA     => true,
            // MBerkasLpdPaud::PROFIL_LEMBAGA   => true,
            MBerkasLpdPaud::NPWP             => true,
            // MBerkasLpdPaud::SK_PELATIHAN     => true,
            MBerkasLpdPaud::SK_KEPENGURUSAN  => true,
            MBerkasLpdPaud::PAKTA_INTEGRITAS => true,
            // MBerkasLpdPaud::BUKU_REKENING    => true,
        ];

        foreach ($paudInstansi->paudInstansiBerkases as $paudInstansiBerkas) {
            if (isset($kBerkases[$paudInstansiBerkas->k_berkas_lpd_paud])) {
                unset($kBerkases[$paudInstansiBerkas->k_berkas_lpd_paud]);
            }
        }
        $isLengkapBerkas = (bool)$kBerkases;

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

    public function berkasDelete(PaudInstansiBerkas $berkas)
    {
        $oldFile = $berkas->file;

        $berkas->delete();

        if ($oldFile) {
            // static::deleteBerkas($oldFile);
        }
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

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function updateProfil(PaudInstansi $paudInstansi, array $data, ?string $foto, ?string $ext)
    {
        $instansi = $paudInstansi->instansi;

        $oldFoto = $instansi->foto ? $instansi->getOriginal('foto') : null;

        $paudInstansi->fill($data);
        if (!$paudInstansi->save()) {
            throw new SaveException("Penyimpanan Data Lembaga Tidak Berhasil");
        }

        $instansi->fill(Arr::only($data, [
            'nama',
            'email',
            'alamat',
            'no_telpon',
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

    public function ajuanCreate(PaudInstansi $paudInstansi)
    {
        $paudInstansi->wkt_ajuan     = Carbon::now();
        $paudInstansi->k_verval_paud = MVervalPaud::DIAJUKAN;

        if (!$paudInstansi->save()) {
            throw new FlowException('Proses ajuan tidak berhasil');
        }

        return $paudInstansi;
    }

    private function validateBatalAjuan(PaudInstansi $paudInstansi)
    {
        if ($paudInstansi->k_verval_paud > MVervalPaud::DIAJUKAN) {
            throw new FlowException('Ajuan sedang dalam proses verifikasi');
        }
    }

    public function ajuanDelete(PaudInstansi $paudInstansi)
    {
        $this->validateBatalAjuan($paudInstansi);

        $paudInstansi->wkt_ajuan     = null;
        $paudInstansi->k_verval_paud = MVervalPaud::KANDIDAT;

        if (!$paudInstansi->save()) {
            throw new FlowException('Proses ajuan tidak berhasil');
        }

        return $paudInstansi;
    }

    public function indexAjuan(array $params)
    {
        $query = $this->query($params['filter'] ?? []);

        if ($kVervalPaud = Arr::get($params, 'filter.k_verval_paud')) {
            $query->whereIn('k_verval_paud', $kVervalPaud);
        } else {
            $query->where('k_verval_paud', '<>', MVervalPaud::KANDIDAT);
        }

        return $query->with(['mVervalPaud']);
    }

    public function vervalUpdate(Akun $akun, PaudInstansi $paudInstansi, array $params)
    {
        $paudInstansi->k_verval_paud  = $params['k_verval_paud'];
        $paudInstansi->wkt_verval     = Carbon::now();
        $paudInstansi->akun_id_verval = $akun->akun_id;
        $paudInstansi->alasan         = $params['alasan'] ?? null;

        if (!$paudInstansi->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $paudInstansi;
    }

    public function setAktif(PaudInstansi $paudInstansi, array $params)
    {
        $paudInstansi->is_aktif = $params['enable'];

        if (!$paudInstansi->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $paudInstansi;
    }

    public function download($params = [])
    {
        $q = $this->query($params)->with(['instansi.mKota', 'instansi.mPropinsi']);

        if (Arr::get($params, 'format') == 'json') {
            return $q->paginate(100);
        }

        $header = [
            'No',
            'Nama Institusi LPD',
            'Alamat Surel',
            'Alamat',
            'Provinsi',
            'Kota/Kabupaten',
            'Kode Pos',
            'Penanggung Jawab',
            'Telpon',
        ];

        $date     = Carbon::now()->format('dmYHi');
        $filename = "Laporan-Institusi-{$date}.xlsx";

        $defaultStyle = (new StyleBuilder())
            ->setFontName('Calibri')
            ->setFontSize(11)
            ->setShouldWrapText(false)
            ->build();
        $headerStyle  = clone $defaultStyle;
        $headerStyle->setFontBold();
        $headerStyle->setCellAlignment(CellAlignment::CENTER);

        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser($filename);
        $writer->setDefaultRowStyle($defaultStyle);

        $writer->addRow(WriterEntityFactory::createRowFromArray($header, $headerStyle));

        $i = 1;
        $q->chunk(1000, function ($paudInstansis) use ($writer, &$i) {
            foreach ($paudInstansis as $paudInstansi) {
                /** @var PaudInstansi $paudInstansi */
                $instansi = $paudInstansi->instansi;

                $row = [
                    $i++,
                    $instansi->nama,
                    $instansi->email,
                    $instansi->alamat,
                    $instansi->mPropinsi->keterangan ?? null,
                    $instansi->mKota->keterangan ?? null,
                    $paudInstansi->kodepos,
                    $paudInstansi->nama_penanggung_jawab,
                    $paudInstansi->telp_penanggung_jawab,
                ];

                $writer->addRow(WriterEntityFactory::createRowFromArray($row));
            }
        });

        $writer->close();
    }
}
