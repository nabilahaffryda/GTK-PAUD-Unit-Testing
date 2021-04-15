<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Akun;
use App\Models\MBerkasPengajarPaud;
use App\Models\MVervalPaud;
use App\Models\PaudAdmin;
use App\Models\PaudPengajar;
use App\Models\PaudPengajarBerkas;
use App\Services\AkunService;
use Arr;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Psp\Models\PspProfil;

class PengajarService
{
    /**
     * @throws FlowException
     */
    public static function uploadBerkas(PaudPengajar $pengajar, int $kBerkas, UploadedFile $file): string
    {
        $akun = $pengajar->akun;

        $ftpPath   = config('filesystems.disks.pengajar-berkas.path');
        $timestamp = date('ymdhis');
        $pathname  = "{$ftpPath}/{$pengajar->tahun}/{$pengajar->angkatan}";
        $filename  = "{$akun->akun_id}-{$kBerkas}-{$timestamp}.{$file->getExtension()}";

        if (!Storage::disk('pengajar-berkas')->putFileAs($pathname, $file, $filename)) {
            throw new FlowException("Unggah Foto Akun tidak berhasil");
        }

        return "{$pathname}/{$filename}";
    }

    /**
     * @return boolean
     */
    public static function deleteBerkas(string $filename)
    {
        $ftpPath = config('filesystems.disks.pengajar-berkas.path');

        $path = sprintf("%s/%s", $ftpPath, $filename);
        return Storage::disk('pengajar-berkas')->delete($path);
    }

    /**
     * @return PaudPengajar|null
     */
    public function getPengajar(Akun $akun)
    {
        /** @var PaudPengajar $pengajar */
        $pengajar = PaudPengajar::query()
            ->where([
                'akun_id'  => $akun->akun_id,
                'tahun'    => config('paud.tahun'),
                'angkatan' => config('paud.angkatan'),
            ])
            ->with(['akun'])
            ->first();

        return $pengajar;
    }

    public function getStatusLengkap(PaudPengajar $pengajar): array
    {
        $akun = $pengajar->akun;

        $isLengkap1 = $akun->nama && $akun->alamat && $akun->email && $akun->k_propinsi && $akun->k_kota
                      && $akun->tgl_lahir && $akun->tmp_lahir && $akun->no_hp
                      && $pengajar->instansi_lulus && $pengajar->is_pcp && $pengajar->pengalaman
                      && $pengajar->k_kualifikasi;

        $isLengkap2 = $pengajar->paudPengajarBerkases()->count() == 5;

        return [
            'profil' => $isLengkap1,
            'berkas' => $isLengkap2,
        ];
    }

    public function fetch(PaudPengajar $pengajar)
    {
        return $pengajar->loadMissing([
            'akun',
            'mVervalPaud',
            'paudPengajarBerkases',
        ]);
    }

    /**
     * @throws SaveException
     */
    public function create(PaudAdmin $admin)
    {
        $pengajar = PaudPengajar::firstOrNew([
            'akun_id'  => $admin->akun_id,
            'tahun'    => $admin->tahun,
            'angkatan' => $admin->angkatan,
        ], [
            'k_verval_paud' => MVervalPaud::KANDIDAT,
        ]);

        $pengajar->admin_id = akunId();
        if (!$pengajar->save()) {
            throw new SaveException("Penyimpanan Data Pengajar tidak berhasil");
        }

        return $pengajar;
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function update(PaudPengajar $pengajar, array $data, ?string $foto, ?string $ext)
    {
        $akun = $pengajar->akun;

        $oldFoto = $akun->foto ? $akun->getOriginal('foto') : null;

        $pengajar->fill($data);
        if (!$pengajar->save()) {
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

    public function updatePeran(PaudPengajar $pengajar, bool $isPembimbing)
    {
        $pengajar->is_pembimbing = $isPembimbing;
        $pengajar->save();
    }

    /**
     * @throws FlowException
     * @throws SaveException
     */
    public function berkasCreate(PaudPengajar $pengajar, int $kBerkas, UploadedFile $file)
    {
        $mBerkas = MBerkasPengajarPaud::findOrFail($kBerkas);

        $oldFile        = null;
        $profilBerkases = $pengajar
            ->paudPengajarBerkases()
            ->where('k_berkas_pengajar_paud', $kBerkas)
            ->get();

        if ($mBerkas->maks == 1 && $profilBerkases->count()) {
            /** @var PaudPengajarBerkas $berkas */
            $berkas  = $profilBerkases->first();
            $oldFile = $berkas->getOriginal('file');
        } else {
            $berkas = new PaudPengajarBerkas([
                'paud_pengajar_id'       => $pengajar->paud_pengajar_id,
                'akun_id'                => $pengajar->akun_id,
                'tahun'                  => $pengajar->tahun,
                'angkatan'               => $pengajar->angkatan,
                'k_berkas_pengajar_paud' => $kBerkas,
            ]);
        }

        $berkas->nama = $file->getClientOriginalName();
        $berkas->file = static::uploadBerkas($pengajar, $kBerkas, $file);
        if (!$pengajar->paudPengajarBerkases()->save($berkas)) {
            throw new SaveException("Penyimpanan Berkas tidak berhasil");
        }

        if ($oldFile) {
            // Disable hapus berkas, untuk backup plan
            // static::deleteBerkas($oldFile);
        }

        return $berkas;
    }

    public function berkasDelete(PaudPengajarBerkas $berkas)
    {
        $oldFile = $berkas->file;

        $berkas->delete();

        if ($oldFile) {
            // static::deleteBerkas($oldFile);
        }
    }
}
