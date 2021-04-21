<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Akun;
use App\Models\MBerkasPembimbingPaud;
use App\Models\PaudAdmin;
use App\Models\PaudPembimbing;
use App\Models\PaudPembimbingBerkas;
use App\Services\AkunService;
use Arr;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PembimbingService
{
    /**
     * @throws FlowException
     */
    public static function uploadBerkas(PaudPembimbing $pembimbing, int $kBerkas, UploadedFile $file): string
    {
        $akun = $pembimbing->akun;

        $ftpPath   = config('filesystems.disks.pembimbing-berkas.path');
        $timestamp = date('ymdhis');
        $pathname  = "{$pembimbing->tahun}/{$pembimbing->angkatan}";
        $filename  = "{$akun->akun_id}-{$kBerkas}-{$timestamp}.{$file->getClientOriginalExtension()}";

        if (!Storage::disk('pembimbing-berkas')->putFileAs("{$ftpPath}/{$pathname}", $file, $filename)) {
            throw new FlowException("Unggah Berkas Pembimbing tidak berhasil");
        }

        return "{$pathname}/{$filename}";
    }

    /**
     * @return bool
     */
    public static function deleteBerkas(string $filename)
    {
        $ftpPath = config('filesystems.disks.pembimbing-berkas.path');

        $path = sprintf("%s/%s", $ftpPath, $filename);
        return Storage::disk('pembimbing-berkas')->delete($path);
    }

    /**
     * @return PaudPembimbing|null
     */
    public function getPembimbing(Akun $akun)
    {
        /** @var PaudPembimbing $pembimbing */
        $pembimbing = PaudPembimbing::query()
            ->where([
                'akun_id'  => $akun->akun_id,
                'tahun'    => config('paud.tahun'),
                'angkatan' => config('paud.angkatan'),
            ])
            ->with(['akun'])
            ->first();

        return $pembimbing;
    }

    public function getStatusLengkap(PaudPembimbing $pembimbing): array
    {
        $akun = $pembimbing->akun;

        $isLengkap1 = $akun->nama && $akun->alamat && $akun->email && $akun->k_propinsi && $akun->k_kota
            && $akun->nik && $pembimbing->prodi
            && $akun->tgl_lahir && $akun->tmp_lahir && $akun->no_hp
            && $pembimbing->lulusan && $pembimbing->is_diklat_dasar && $pembimbing->pengalaman
            && $pembimbing->k_kualifikasi
            && $pembimbing->pengalaman && is_array($pembimbing->pengalaman) && count($pembimbing->pengalaman) >= 1;

        $isLengkap2 = $pembimbing->paudPembimbingBerkases()->count() == 6;

        return [
            'profil' => $isLengkap1,
            'berkas' => $isLengkap2,
        ];
    }

    public function isStatusLengkap(PaudPembimbing $pembimbing): bool
    {
        $status = $this->getStatusLengkap($pembimbing);
        return array_search(false, $status, true) === false;
    }

    public function fetch(PaudPembimbing $pembimbing)
    {
        return $pembimbing->loadMissing([
            'akun',
            'mVervalPaud',
            'paudPembimbingBerkases',
        ]);
    }

    /**
     * @throws SaveException
     */
    public function create(PaudAdmin $admin, array $params)
    {
        $pembimbing = PaudPembimbing::firstOrNew([
            'akun_id'  => $admin->akun_id,
            'tahun'    => $admin->tahun,
            'angkatan' => $admin->angkatan,
        ], $params);

        $pembimbing->admin_id = akunId();
        if (!$pembimbing->save()) {
            throw new SaveException("Penyimpanan Data Pembimbing tidak berhasil");
        }

        return $pembimbing;
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function update(PaudPembimbing $pembimbing, array $data, ?string $foto, ?string $ext)
    {
        $akun = $pembimbing->akun;

        $oldFoto = $akun->foto ? $akun->getOriginal('foto') : null;

        $pembimbing->fill($data);
        if (!$pembimbing->save()) {
            throw new SaveException("Penyimpanan Data Profil Tidak Berhasil");
        }

        $akun->fill(Arr::only($data, [
            'alamat',
            'kelamin',
            'kodepos',
            'k_kota',
            'k_propinsi',
            'nama',
            'nik',
            'nip',
            'no_hp',
            'tgl_lahir',
            'tmp_lahir',
        ]));

        if ($foto && $ext) {
            $akun->foto = app(AkunService::class)->uploadFoto($akun, $foto, $ext);
        }

        if (!$akun->save()) {
            throw new SaveException("Penyimpanan Akun tidak berhasil");
        }

        if ($foto && $ext && $oldFoto) {
            // app(AkunService::class)->deleteFoto($oldFoto);
        }
    }

    /**
     * @throws FlowException
     * @throws SaveException
     */
    public function berkasCreate(PaudPembimbing $pembimbing, int $kBerkas, UploadedFile $file)
    {
        $mBerkas = MBerkasPembimbingPaud::findOrFail($kBerkas);

        $oldFile        = null;
        $profilBerkases = $pembimbing
            ->paudPembimbingBerkases()
            ->where('k_berkas_pembimbing_paud', $kBerkas)
            ->get();

        if ($mBerkas->maks == 1 && $profilBerkases->count()) {
            /** @var PaudPembimbingBerkas $berkas */
            $berkas  = $profilBerkases->first();
            $oldFile = $berkas->getOriginal('file');
        } else {
            $berkas = new PaudPembimbingBerkas([
                'paud_pembimbing_id'       => $pembimbing->paud_pembimbing_id,
                'akun_id'                  => $pembimbing->akun_id,
                'tahun'                    => $pembimbing->tahun,
                'angkatan'                 => $pembimbing->angkatan,
                'k_berkas_pembimbing_paud' => $kBerkas,
            ]);
        }

        $berkas->nama = $file->getClientOriginalName();
        $berkas->file = static::uploadBerkas($pembimbing, $kBerkas, $file);
        if (!$pembimbing->paudPembimbingBerkases()->save($berkas)) {
            throw new SaveException("Penyimpanan Berkas tidak berhasil");
        }

        if ($oldFile) {
            // Disable hapus berkas, untuk backup plan
            // static::deleteBerkas($oldFile);
        }

        return $berkas;
    }

    /**
     * @throws Exception
     */
    public function berkasDelete(PaudPembimbingBerkas $berkas)
    {
        $oldFile = $berkas->file;

        $berkas->delete();

        if ($oldFile) {
            // static::deleteBerkas($oldFile);
        }
    }
}
