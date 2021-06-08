<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Akun;
use App\Models\MBerkasPetugasPaud;
use App\Models\MDiklatPaud;
use App\Models\MPetugasPaud;
use App\Models\MVervalPaud;
use App\Models\PaudAdmin;
use App\Models\PaudPetugas;
use App\Models\PaudPetugasBerkas;
use App\Models\PaudPetugasDiklat;
use App\Models\PaudPetugasPeran;
use App\Models\PaudPetugasPeranBerkas;
use App\Services\AkunService;
use Arr;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PetugasService
{
    /**
     * @throws FlowException
     */
    public static function uploadBerkas(PaudPetugas $petugas, string $jenis, int $kBerkas, UploadedFile $file): string
    {
        $akun = $petugas->akun;

        $ftpPath   = config('filesystems.disks.petugas-berkas.path');
        $timestamp = date('ymdhis');
        $pathname  = "{$petugas->tahun}/{$petugas->angkatan}";
        $filename  = "{$akun->akun_id}-{$jenis}-{$kBerkas}-{$timestamp}.{$file->getClientOriginalExtension()}";

        if (!Storage::disk('petugas-berkas')->putFileAs("{$ftpPath}/{$pathname}", $file, $filename)) {
            throw new FlowException("Unggah Berkas Petugas tidak berhasil");
        }

        return "{$pathname}/{$filename}";
    }

    /**
     * @return bool
     */
    public static function deleteBerkas(string $filename)
    {
        $ftpPath = config('filesystems.disks.petugas-berkas.path');

        $path = sprintf("%s/%s", $ftpPath, $filename);
        return Storage::disk('petugas-berkas')->delete($path);
    }

    /**
     * @return PaudPetugas|null
     */
    public function getPetugas(Akun $akun)
    {
        /** @var PaudPetugas $petugas */
        $petugas = PaudPetugas::query()
            ->where([
                'akun_id'  => $akun->akun_id,
                'tahun'    => config('paud.tahun'),
                'angkatan' => config('paud.angkatan'),
            ])
            ->with(['akun'])
            ->first();

        return $petugas;
    }

    public function getStatusLengkap(PaudPetugas $petugas): array
    {
        $akun = $petugas->akun;

        $isLengkap1 = $akun->nama && $akun->email && $akun->no_hp
            && $akun->tgl_lahir && $akun->tmp_lahir
            && $akun->alamat && $akun->k_propinsi && $akun->k_kota
            && $akun->nik && $petugas->prodi && $petugas->lulusan && $petugas->k_kualifikasi;

        $isLengkap2 = $petugas->paudPetugasDiklats()->count() > 0;

        $isLengkap3 = $petugas->paudPetugasBerkases()->count() >= 4;

        return [
            'profil' => $isLengkap1,
            'diklat' => $isLengkap2,
            'berkas' => $isLengkap3,
        ];
    }

    public function isStatusLengkap(PaudPetugas $petugas): bool
    {
        $status = $this->getStatusLengkap($petugas);
        return array_search(false, $status, true) === false;
    }

    public function index(array $params)
    {
        return PaudPetugas::query()
            ->where([
                'tahun'    => Arr::get($params, 'tahun', config('paud.tahun')),
                'angkatan' => Arr::get($params, 'angkatan', config('paud.angkatan')),
            ])
            ->whereHas('paudPetugasPerans', function (Builder $query) use ($params) {
                $query
                    ->where([
                        'tahun'    => Arr::get($params, 'tahun', config('paud.tahun')),
                        'angkatan' => Arr::get($params, 'angkatan', config('paud.angkatan')),
                        ['k_verval_paud', '<>', MVervalPaud::KANDIDAT],
                    ]);
            })
            ->with(['akun', 'paudPetugasPerans.mVervalPaud',
                    'paudPetugasPerans.akunVerval:akun_id,nama,email,no_telpon,no_hp']);
    }

    public function fetch(PaudPetugas $petugas)
    {
        return $petugas->loadMissing([
            'akun',
            'akun.mKota',
            'akun.mPropinsi',
            'paudPetugasDiklats',
            'paudPetugasBerkases',
            'paudPetugasPerans',
            'instansiKota',
            'instansiPropinsi',
        ]);
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function create(PaudAdmin $admin, array $params)
    {
        $petugas = PaudPetugas::firstOrNew([
            'akun_id'  => $admin->akun_id,
            'tahun'    => $admin->tahun,
            'angkatan' => $admin->angkatan,
        ], $params);

        if ($petugas->exists) {
            throw new FlowException("Perubahan data petugas bisa dilakukan mandiri dari akun petugas.");
        }

        $petugas->instansi_id = $admin->instansi_id;
        $petugas->created_by  = akunId();
        if (!$petugas->save()) {
            throw new SaveException("Penyimpanan Data Petugas tidak berhasil");
        }

        if ($petugas->k_petugas_paud == MPetugasPaud::PENGAJAR_TAMBAHAN) {
            $peran = new PaudPetugasPeran([
                'paud_petugas_id' => $petugas->paud_petugas_id,
                'akun_id'         => $admin->akun_id,
                'tahun'           => $petugas->tahun,
                'angkatan'        => $petugas->angkatan,
                'k_verval_paud'   => MVervalPaud::KANDIDAT,
            ]);

            $petugas->paudPetugasPerans()->save($peran);
        }

        return $petugas;
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function update(PaudPetugas $petugas, array $data, ?string $foto, ?string $ext)
    {
        $akun = $petugas->akun;

        $oldFoto = $akun->foto ? $akun->getOriginal('foto') : null;

        $petugas->fill($data);
        if (!$petugas->save()) {
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

    public function delete(PaudAdmin $admin)
    {
        $petugas = PaudPetugas::firstWhere([
            'akun_id'  => $admin->akun_id,
            'tahun'    => $admin->tahun,
            'angkatan' => $admin->angkatan,
        ]);

        if (!$petugas) {
            return;
        }

        if ($petugas->k_petugas_paud == MPetugasPaud::PENGAJAR_TAMBAHAN) {
            $peran = PaudPetugasPeran::firstWhere([
                'paud_petugas_id' => $petugas->paud_petugas_id,
            ]);

            if ($peran) {
                $berkases = PaudPetugasPeranBerkas::where([
                    'paud_petugas_peran_id' => $peran->paud_petugas_peran_id,
                ])->get();

                $peran->delete();
                foreach ($berkases as $berkas) {
                    //TODO: delete file
                    $berkas->delete();
                }
            }
        }

        $diklats = PaudPetugasDiklat::where([
            'paud_petugas_id' => $petugas->paud_petugas_id,
        ])->get();

        foreach ($diklats as $diklat) {
            //TODO: delete file
            $diklat->delete();
        }

        $berkases = PaudPetugasBerkas::where([
            'paud_petugas_id' => $petugas->paud_petugas_id,
        ])->get();

        foreach ($berkases as $berkas) {
            //TODO: delete file
            $berkas->delete();
        }

        $petugas->delete();
    }

    /**
     * @throws FlowException
     */
    public function ajuanCreate(PaudPetugas $petugas)
    {
        $peran = PaudPetugasPeran::where([
            'paud_petugas_id' => $petugas->paud_petugas_id,
            'tahun'           => $petugas->tahun,
            'angkatan'        => $petugas->angkatan,
        ])->first();

        if (!($peran->isVervalKandidat() || $peran->isVervalRevisi())) {
            throw new FlowException("Anda telah melakukan Pengajuan. Untuk melakukan perubahan silakan batalkan Ajuan jika memungkinkan");
        }

        if (!$this->isStatusLengkap($petugas)) {
            throw new FlowException("Pastikan Anda telah melengkapi Profil, Pengalaman Diklat dan Berkas");
        }

        $peran->k_verval_paud = MVervalPaud::DIAJUKAN;
        $peran->save();
    }

    /**
     * @throws FlowException|SaveException
     */
    public function ajuanDelete(PaudPetugas $petugas)
    {
        $peran = PaudPetugasPeran::where([
            'paud_petugas_id' => $petugas->paud_petugas_id,
            'tahun'           => $petugas->tahun,
            'angkatan'        => $petugas->angkatan,
        ])->first();

        if ($peran->isVervalKandidat() || $peran->isVervalRevisi()) {
            throw new FlowException("Anda belum melakukan Pengajuan");
        }

        if (!$peran->isVervalDiajukan()) {
            throw new FlowException("Batal Ajuan sudah tidak dapat dilakukan");
        }

        $peran->k_verval_paud = MVervalPaud::KANDIDAT;
        if (!$peran->save()) {
            throw new SaveException("Penyimpanan Status Ajuan tidak berhasil");
        }
    }

    /**
     * @throws FlowException
     * @throws SaveException
     */
    public function berkasCreate(PaudPetugas $petugas, int $kBerkas, UploadedFile $file)
    {
        $mBerkas = MBerkasPetugasPaud::findOrFail($kBerkas);

        $oldFile        = null;
        $profilBerkases = $petugas
            ->paudPetugasBerkases()
            ->where('k_berkas_petugas_paud', $kBerkas)
            ->get();

        if ($mBerkas->maks == 1 && $profilBerkases->count()) {
            /** @var PaudPetugasBerkas $berkas */
            $berkas  = $profilBerkases->first();
            $oldFile = $berkas->getOriginal('file');
        } else {
            $berkas = new PaudPetugasBerkas([
                'paud_petugas_id'       => $petugas->paud_petugas_id,
                'akun_id'               => $petugas->akun_id,
                'tahun'                 => $petugas->tahun,
                'angkatan'              => $petugas->angkatan,
                'k_berkas_petugas_paud' => $kBerkas,
            ]);
        }

        $berkas->nama = $file->getClientOriginalName();
        $berkas->file = static::uploadBerkas($petugas, 'berkas', $kBerkas, $file);
        if (!$petugas->PaudPetugasBerkases()->save($berkas)) {
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
    public function berkasDelete(PaudPetugasBerkas $berkas)
    {
        $oldFile = $berkas->file;

        $berkas->delete();

        if ($oldFile) {
            // static::deleteBerkas($oldFile);
        }
    }

    private function getPeranPetugas(PaudPetugas $petugas)
    {
        return PaudPetugasPeran::where([
            'paud_petugas_id' => $petugas->paud_petugas_id,
            'tahun'           => $petugas->tahun,
            'angkatan'        => $petugas->angkatan,
        ])->first();
    }

    /**
     * @throws FlowException
     */
    public function ajuanVerval(Akun $akun, PaudPetugas $petugas, array $params)
    {
        $peran = $this->getPeranPetugas($petugas);

        if (!$peran || !$peran->isVervalDiajukan()) {
            throw new FlowException("Ajuan data petugas tidak ditemukan");
        }

        $peran->k_verval_paud  = $params['k_verval_paud'];
        $peran->wkt_verval     = Carbon::now();
        $peran->akun_id_verval = $akun->akun_id;
        $peran->alasan         = $params['alasan'] ?? null;

        if (!$peran->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $petugas;
    }

    public function batalVerval(Akun $akun, PaudPetugas $petugas)
    {
        $peran = $this->getPeranPetugas($petugas);

        if (!$peran) {
            throw new FlowException("Ajuan data petugas tidak ditemukan");
        }

        if (!in_array($peran->k_verval_paud, [MVervalPaud::DITOLAK, MVervalPaud::DISETUJUI])) {
            throw new FlowException("Berkas ajuan belum diverval");
        }

        if ($peran->akun_id_verval <> $akun->akun_id) {
            throw new FlowException("Anda tidak dberhak membatalkan hasil verval");
        }

        $peran->k_verval_paud  = MVervalPaud::DIPROSES;
        $peran->wkt_verval     = Carbon::now();
        $peran->akun_id_verval = $akun->akun_id;
        $peran->alasan         = null;

        if (!$peran->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $petugas;
    }


    public function kunci(Akun $akun, PaudPetugas $petugas)
    {
        $peran = $this->getPeranPetugas($petugas);

        if (!$peran || !$peran->k_verval_paud == MVervalPaud::DIPROSES) {
            throw new FlowException("Ajuan data petugas tidak ditemukan");
        }

        $peran->k_verval_paud  = MVervalPaud::DIPROSES;
        $peran->wkt_verval     = Carbon::now();
        $peran->akun_id_verval = $akun->akun_id;
        $peran->alasan         = null;

        if (!$peran->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $petugas;
    }

    public function batalKunci(Akun $akun, PaudPetugas $petugas)
    {
        $peran = $this->getPeranPetugas($petugas);

        if (!$peran) {
            throw new FlowException("Ajuan data petugas tidak ditemukan");
        }

        if ($peran->k_verval_paud != MVervalPaud::DIPROSES) {
            throw new FlowException("Ajuan berkas tidak dalam kondisi diproses");
        }

        $peran->k_verval_paud  = MVervalPaud::DIAJUKAN;
        $peran->wkt_verval     = null;
        $peran->akun_id_verval = null;
        $peran->alasan         = null;

        if (!$peran->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $petugas;
    }

    /**
     * @throws FlowException
     */
    public function diklatUpdate(PaudPetugas $petugas, array $data)
    {
        $oldFiles = [];

        $diklatNews = [];
        $diklatOlds = PaudPetugasDiklat::query()
            ->where([
                'paud_petugas_id' => $petugas->paud_petugas_id,
                'tahun'           => $petugas->tahun,
                'angkatan'        => $petugas->angkatan,
            ])
            ->get()
            ->keyBy('paud_petugas_diklat_id');

        $data = collect($data['data']);

        $mDiklats = MDiklatPaud::get()->keyBy('k_diklat_paud');

        foreach ($data as $index => $item) {
            if (isset($item['paud_petugas_diklat_id'])) {
                /** @var PaudPetugasDiklat $diklat */
                $diklat = $diklatOlds->pull($item['paud_petugas_diklat_id']);
                if (!$diklat) {
                    throw new FlowException("Data diklat ke {$index} tidak ditemukan");
                }

            } else {
                $diklat = new PaudPetugasDiklat();

                $diklat->paud_petugas_id = $petugas->paud_petugas_id;
                $diklat->akun_id         = $petugas->akun_id;
                $diklat->tahun           = $petugas->tahun;
                $diklat->angkatan        = $petugas->angkatan;
                $diklat->k_diklat_paud   = $item['k_diklat_paud'];

            }

            $diklatNews[] = $diklat;

            if ($diklat->k_diklat_paud == MDiklatPaud::DIKLAT_LAINNYA) {
                $diklat->nama      = $item['nama'];
                $diklat->tingkatan = $item['tingkatan'];
            } else {
                $diklat->nama                  = $mDiklats[$diklat->k_diklat_paud]->keterangan;
                $diklat->k_tingkat_diklat_paud = $item['k_tingkat_diklat_paud'];
            }

            $diklat->penyelenggara = $item['penyelenggara'];
            $diklat->tahun_diklat  = $item['tahun_diklat'];

            /** @var UploadedFile $file */
            if ($file = $item['file'] ?? null) {
                if ($diklat->exists && $diklat->file) {
                    $oldFiles[] = $diklat->file;
                }

                $diklat->nama_file = $file->getClientOriginalName();
                $diklat->file      = static::uploadBerkas($petugas, 'diklat', $diklat->k_diklat_paud, $file);
            }
        }

        foreach ($diklatNews as $diklat) {
            $diklat->save();
        }

        foreach ($diklatOlds as $diklat) {
            $oldFiles[] = $diklat->file;

            $diklat->delete();
        }

        foreach ($oldFiles as $oldFile) {
            // Disable hapus berkas, untuk backup plan
            // static::deleteBerkas($oldFile);
        }
    }
}
