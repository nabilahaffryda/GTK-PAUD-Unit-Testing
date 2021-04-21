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
use Carbon\Carbon;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
        $pathname  = "{$pengajar->tahun}/{$pengajar->angkatan}";
        $filename  = "{$akun->akun_id}-{$kBerkas}-{$timestamp}.{$file->getClientOriginalExtension()}";

        if (!Storage::disk('pengajar-berkas')->putFileAs("{$ftpPath}/{$pathname}", $file, $filename)) {
            throw new FlowException("Unggah Foto Akun tidak berhasil");
        }

        return "{$pathname}/{$filename}";
    }

    /**
     * @return bool
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
            && $akun->nik && $pengajar->prodi
            && $akun->tgl_lahir && $akun->tmp_lahir && $akun->no_hp
            && $pengajar->lulusan && $pengajar->k_pcp_paud && $pengajar->pengalaman
            && $pengajar->k_kualifikasi
            && $pengajar->pengalaman && is_array($pengajar->pengalaman) && count($pengajar->pengalaman) >= 1;

        $isLengkap2 = $pengajar->paudPengajarBerkases()->count() == 4;

        return [
            'profil' => $isLengkap1,
            'berkas' => $isLengkap2,
        ];
    }

    public function isStatusLengkap(PaudPengajar $pengajar): bool
    {
        $status = $this->getStatusLengkap($pengajar);
        return array_search(false, $status, true) === false;
    }

    /**
     * @throws FlowException
     */
    public function validateTambahan(PaudPengajar $pengajar)
    {
        if (!$pengajar->is_tambahan) {
            throw new FlowException("Peran anda bukan sebagai pengajar tambahan");
        }
    }

    public function index(array $params)
    {
        return PaudPengajar::query()
            ->where([
                'is_tambahan' => 1,
                'tahun'       => Arr::get($params, 'tahun', config('paud.tahun')),
                'angkatan'    => Arr::get($params, 'angkatan', config('paud.angkatan')),
            ])
            ->with('akun', 'mVervalPaud');
    }

    public function fetch(PaudPengajar $pengajar)
    {
        return $pengajar->loadMissing([
            'akun',
            'mPcpPaud',
            'mVervalPaud',
            'paudPengajarBerkases',
        ]);
    }

    /**
     * @throws SaveException
     */
    public function create(PaudAdmin $admin, array $params)
    {
        $pengajar = PaudPengajar::firstOrNew([
            'akun_id'  => $admin->akun_id,
            'tahun'    => $admin->tahun,
            'angkatan' => $admin->angkatan,
        ], $params);

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

    /**
     * @throws FlowException
     */
    public function updatePeran(PaudPengajar $pengajar, bool $isPembimbing)
    {
        $this->validateTambahan($pengajar);

        if ($pengajar->is_pembimbing !== null) {
            throw new FlowException("Anda sudah menentukan peran anda");
        }

        $pengajar->is_pembimbing = $isPembimbing;
        $pengajar->save();
    }

    /**
     * @throws FlowException
     */
    public function ajuanCreate(PaudPengajar $pengajar)
    {
        $this->validateTambahan($pengajar);

        if (!($pengajar->isVervalKandidat() || $pengajar->isVervalRevisi())) {
            throw new FlowException("Anda telah melakukan Pengajuan. Untuk melakukan perubahan silakan batalkan Ajuan jika memungkinkan");
        }

        if (!$this->isStatusLengkap($pengajar)) {
            throw new FlowException("Pastikan Anda telah melengkapi Profil dan Berkas");
        }

        $pengajar->k_verval_paud = MVervalPaud::KANDIDAT;
        $pengajar->save();
    }

    /**
     * @throws FlowException|SaveException
     */
    public function ajuanDelete(PaudPengajar $pengajar)
    {
        $this->validateTambahan($pengajar);

        if ($pengajar->isVervalKandidat() || $pengajar->isVervalRevisi()) {
            throw new FlowException("Anda belum melakukan Pengajuan");
        }

        if (!$pengajar->isVervalDiajukan()) {
            throw new FlowException("Batal Ajuan sudah tidak dapat dilakukan");
        }

        $pengajar->k_verval_paud = MVervalPaud::KANDIDAT;
        if (!$pengajar->save()) {
            throw new SaveException("Penyimpanan Status Ajuan tidak berhasil");
        }
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

    /**
     * @throws Exception
     */
    public function berkasDelete(PaudPengajarBerkas $berkas)
    {
        $oldFile = $berkas->file;

        $berkas->delete();

        if ($oldFile) {
            // static::deleteBerkas($oldFile);
        }
    }

    public function vervalUpdate(Akun $akun, PaudPengajar $pengajar, array $params)
    {
        $pengajar->k_verval_paud  = $params['k_verval_paud'];
        $pengajar->wkt_verval     = Carbon::now();
        $pengajar->akun_id_verval = $akun->akun_id;
        $pengajar->alasan         = $params['alasan'] ?? null;

        if (!$pengajar->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $pengajar;
    }
}
