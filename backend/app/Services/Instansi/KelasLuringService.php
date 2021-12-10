<?php


namespace App\Services\Instansi;


use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Akun;
use App\Models\MKonfirmasiPaud;
use App\Models\MPetugasPaud;
use App\Models\MVervalPaud;
use App\Models\PaudDiklatLuring;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPesertaLuring;
use App\Models\PaudKelasPetugasLuring;
use App\Models\PaudPesertaNonptk;
use App\Models\PaudPetugas;
use App\Models\Ptk;
use Arr;
use Carbon\Carbon;
use DB;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class KelasLuringService
{
    public function index(PaudDiklatLuring $diklat, array $params): Builder
    {
        $query = PaudKelasLuring::query()
            ->where('paud_kelas_luring.paud_diklat_luring_id', $diklat->paud_diklat_luring_id)
            ->with(['mKelurahan', 'mKecamatan', 'mVervalPaud']);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where('paud_kelas_luring.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    /**
     * @throws SaveException
     */
    public function create(PaudDiklatLuring $diklat, array $params): PaudKelasLuring
    {
        $kelas                        = new PaudKelasLuring($params);
        $kelas->tahun                 = $diklat->tahun;
        $kelas->angkatan              = $diklat->angkatan;
        $kelas->paud_diklat_luring_id = $diklat->paud_diklat_luring_id;
        $kelas->k_verval_paud         = MVervalPaud::KANDIDAT;
        $kelas->created_by            = akunId();

        if (!$kelas->save()) {
            throw new SaveException("Proses Tambah Kelas Tidak berhasil");
        }

        return $kelas->load(['mVervalPaud', 'paudDiklatLuring', 'paudMapelKelas']);
    }

    /**
     * @throws FlowException
     * @throws SaveException
     */
    public function update(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params): PaudKelasLuring
    {
        $this->validateKelas($diklat, $kelas);

        $kelas->fill($params);
        $kelas->updated_by = akunId();
        if (!$kelas->save()) {
            throw new SaveException("Proses simpan data kelas tida berhasil");
        }

        return $kelas;
    }

    /**
     * @throws FlowException
     */
    public function fetch(PaudDiklatLuring $diklat, PaudKelasLuring $kelas): PaudKelasLuring
    {
        $this->validateKelas($diklat, $kelas);

        return $kelas->load(['mVervalPaud', 'mKecamatan', 'mKelurahan', 'paudDiklatLuring.instansi', 'paudDiklatLuring.paudInstansi', 'paudMapelKelas']);
    }

    /**
     * @throws FlowException
     */
    public function validateKelas(PaudDiklatLuring $diklat, PaudKelasLuring $kelas)
    {
        if ($kelas->paud_diklat_luring_id <> $diklat->paud_diklat_luring_id) {
            throw new FlowException('Kelas tidak ditemukan');
        }
    }

    /**
     * @throws FlowException
     */
    public function validateKelasBaru(PaudKelasLuring $kelas)
    {
        if ($kelas->k_verval_paud != MVervalPaud::KANDIDAT) {
            throw new FlowException('Kelas sudah diajukan/diverval');
        }
    }

    /**
     * @throws FlowException
     */
    public function validateKelasAjuan(PaudKelasLuring $kelas)
    {
        if ($kelas->k_verval_paud == MVervalPaud::KANDIDAT) {
            throw new FlowException('Kelas belum diajukan');
        }

        if (!in_array($kelas->k_verval_paud, [MVervalPaud::DIAJUKAN, MVervalPaud::REVISI])) {
            throw new FlowException('Kelas sudah diverval');
        }
    }

    /**
     * @throws FlowException
     */
    public function indexPeserta(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params): Builder
    {
        $this->validateKelas($diklat, $kelas);

        $query = PaudKelasPesertaLuring::query()
            ->where('paud_kelas_peserta_luring.paud_kelas_luring_id', '=', $kelas->paud_kelas_luring_id)
            ->with([
                'ptk:ptk_id,nama,email',
                'paudPesertaNonptk:paud_peserta_nonptk_id,nama,email',
                'mKonfirmasiPaud',
            ]);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->join('ptk', 'ptk.ptk_id', '=', 'paud_kelas_peserta_luring.ptk_id')
                ->where('ptk.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    /**
     * @throws FlowException
     */
    public function indexPetugas(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params): Builder
    {
        $this->validateKelas($diklat, $kelas);

        $query = PaudKelasPetugasLuring::query()
            ->where('paud_kelas_petugas_luring.paud_kelas_luring_id', '=', $kelas->paud_kelas_luring_id)
            ->where('paud_kelas_petugas_luring.k_petugas_paud', '=', $params['k_petugas_paud'])
            ->with([
                'akun',
                'akun.mKota',
                'akun.mPropinsi',
                'mKonfirmasiPaud',
            ]);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->join('akun', 'akun.akun_id', '=', 'paud_kelas_petugas_luring.akun_id')
                ->where('akun.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    /**
     * @throws FlowException
     */
    public function indexPesertaKandidat(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params, int $kJenjang)
    {
        $this->validateKelas($diklat, $kelas);

        return app(PesertaService::class)->queryKandidat($diklat->k_kota, kJenjang: $kJenjang)
            ->when(Arr::get($params, 'keyword'), function (Builder $query, $keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query
                        ->orWhere('ptk.ptk_id', '=', $keyword)
                        ->orWhere('ptk.nama', 'like', '%' . $keyword . '%')
                        ->orWhere('ptk.email', 'like', '%' . $keyword . '%');
                });
            });
    }

    /**
     * @throws FlowException
     */
    public function indexPesertaKandidatSimpatika(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params)
    {
        $this->validateKelas($diklat, $kelas);
        return app(PesertaService::class)->kandidatSimpatika($diklat->mKota, $params);
    }

    /**
     * @throws FlowException
     */
    public function indexPesertaKandidatNonPtk(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params)
    {
        $this->validateKelas($diklat, $kelas);

        return PaudPesertaNonptk::query()
            ->select('paud_peserta_nonptk.*')
            ->where([
                'paud_peserta_nonptk.paud_instansi_id'      => $diklat->paud_instansi_id,
                'paud_peserta_nonptk.k_propinsi'            => $diklat->k_propinsi,
                'paud_peserta_nonptk.k_kota'                => $diklat->k_kota,
                'paud_peserta_nonptk.k_diklat_paud'         => $diklat->k_diklat_paud,
                'paud_peserta_nonptk.k_jenjang_diklat_paud' => $diklat->k_jenjang_diklat_paud,
            ])
            ->when(Arr::get($params, 'keyword'), function (Builder $query, $keyword) {
                $query->where([
                    ['paud_peserta_nonptk.nik', 'like', '%' . $keyword . '%'],
                    ['paud_peserta_nonptk.nama', 'like', '%' . $keyword . '%'],
                    ['paud_peserta_nonptk.email', 'like', '%' . $keyword . '%'],
                ], boolean: 'or');
            });
    }

    /**
     * @throws FlowException
     */
    public function indexPetugasKandidat(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params)
    {
        $this->validateKelas($diklat, $kelas);

        $query = app(PetugasService::class)
            ->queryKandidat(
                $diklat->instansi_id,
                $diklat->k_kota,
                $params['k_petugas_paud'],
                kelasLuringId: $kelas->paud_kelas_luring_id,
                tglMulai: $diklat->tgl_mulai,
                tglSelesai: $diklat->tgl_selesai,
            )
            ->with(['akun:akun_id,nama,email']);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->join('akun', 'akun.akun_id', '=', 'paud_petugas.akun_id')
                ->where(function ($query) use ($keyword) {
                    $query
                        ->orWhere('akun.nama', 'like', '%' . $keyword . '%')
                        ->orWhere('akun.email', 'like', '%' . $keyword . '%');
                });
        }

        return $query;
    }

    /**
     * @throws FlowException
     */
    public function createPeserta(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params, int $kJenjang = null, int $kSumber = null): Collection
    {
        $this->validateKelas($diklat, $kelas);
        $this->validateKelasBaru($kelas);

        $ptks = app(PesertaService::class)->queryKandidat($diklat->k_kota, kJenjang: $kJenjang, kSumber: $kSumber, kelasLuringId: $kelas->paud_kelas_luring_id)
            ->whereIn('ptk.ptk_id', $params['ptk_id'])
            ->get();

        if ($diff = array_diff($params['ptk_id'], $ptks->pluck('ptk_id')->all())) {
            $ptkIds = implode(', ', $diff);

            throw new FlowException("Peserta dengan PTK_ID {$ptkIds} tidak ditemukan");
        }

        $existing = PaudKelasPesertaLuring::query()
            ->where([
                'paud_kelas_luring_id' => $kelas->paud_kelas_luring_id,
                'is_nonptk'            => '0',
            ])
            ->get()
            ->keyBy('ptk_id');

        $jmlPeserta = $existing->count() + count($params['ptk_id']);
        if ($jmlPeserta > 40) {
            throw new FlowException("Jumlah peserta maksimal 40 orang");
        }

        $result = [];
        /** @var Ptk $ptk */
        foreach ($ptks as $ptk) {
            if ($existing->has($ptk->ptk_id)) {
                $result[] = $existing->get($ptk->ptk_id);
                continue;
            }

            $peserta = new PaudKelasPesertaLuring();
            $peserta->fill($params);
            $peserta->paud_kelas_luring_id = $kelas->paud_kelas_luring_id;
            $peserta->tahun                = $kelas->tahun;
            $peserta->angkatan             = $kelas->angkatan;
            $peserta->is_nonptk            = 0;
            $peserta->ptk_id               = $ptk->ptk_id;
            $peserta->k_konfirmasi_paud    = MKonfirmasiPaud::BELUM_KONFIRMASI;
            $peserta->created_by           = akunId();

            if (!$peserta->save()) {
                throw new FlowException("Peserta tidak berhasil disimpan");
            }

            $result[] = $peserta;
        }

        return PaudKelasPesertaLuring::newModelInstance()->newCollection($result);
    }

    /**
     * @throws FlowException|GuzzleException
     */
    public function createPesertaSimpatika(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params): Collection
    {
        $ptkIds = $params['ptk_id'];

        app(PesertaService::class)->createSimpatika($diklat->mKota, $ptkIds);

        return $this->createPeserta($diklat, $kelas, $params, kSumber: 9);
    }

    /**
     * @throws FlowException
     * @throws Exception
     */
    public function createPesertaNonPtk(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params): Collection
    {
        $this->validateKelas($diklat, $kelas);
        $this->validateKelasBaru($kelas);

        /** @var PaudPesertaNonptk[]|Collection $nonPtks */
        $nonPtks = PaudPesertaNonptk::query()
            ->select('paud_peserta_nonptk.*')
            ->where([
                'paud_instansi_id'      => $diklat->paud_instansi_id,
                'k_propinsi'            => $diklat->k_propinsi,
                'k_kota'                => $diklat->k_kota,
                'k_diklat_paud'         => $diklat->k_diklat_paud,
                'k_jenjang_diklat_paud' => $diklat->k_jenjang_diklat_paud,
            ])
            ->whereIn('paud_peserta_nonptk_id', $params['paud_peserta_nonptk_id'])
            ->get();

        if ($diff = array_diff($params['paud_peserta_nonptk_id'], $nonPtks->pluck('paud_peserta_nonptk_id')->all())) {
            throw new Exception(count($diff) . " peserta non dapodik tidak ditemukan");
        }

        $existing = PaudKelasPesertaLuring::query()
            ->where([
                'paud_kelas_luring_id' => $kelas->paud_kelas_luring_id,
                'is_nonptk'            => '1',
            ])
            ->get()
            ->keyBy('paud_peserta_nonptk_id');

        $jmlPeserta = $existing->count() + count($params['paud_peserta_nonptk_id']);
        if ($jmlPeserta > 40) {
            throw new FlowException("Jumlah peserta maksimal 40 orang");
        }

        $result = [];
        foreach ($nonPtks as $nonPtk) {
            if ($existing->has($nonPtk->paud_peserta_nonptk_id)) {
                $result[] = $existing->get($nonPtk->paud_peserta_nonptk_id);
                continue;
            }

            $peserta = new PaudKelasPesertaLuring();
            $peserta->fill($params);
            $peserta->paud_kelas_luring_id   = $kelas->paud_kelas_luring_id;
            $peserta->tahun                  = $kelas->tahun;
            $peserta->angkatan               = $kelas->angkatan;
            $peserta->is_nonptk              = 1;
            $peserta->paud_peserta_nonptk_id = $nonPtk->paud_peserta_nonptk_id;
            $peserta->k_konfirmasi_paud      = MKonfirmasiPaud::BERSEDIA;
            $peserta->created_by             = akunId();

            if (!$peserta->save()) {
                throw new FlowException("Peserta tidak berhasil disimpan");
            }

            $result[] = $peserta;
        }

        return PaudKelasPesertaLuring::newModelInstance()->newCollection($result);
    }

    /**
     * @throws FlowException
     */
    public function createPetugas(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, array $params): Collection
    {
        $this->validateKelas($diklat, $kelas);
        $this->validateKelasBaru($kelas);

        $jmlPetugas = $kelas->paudKelasPetugasLurings()
            ->whereIn('k_petugas_paud', [$params['k_petugas_paud']])
            ->count();

        $petugases = app(PetugasService::class)->validateNewPetugasKelas(
            $params['k_petugas_paud'],
            $jmlPetugas,
            $kelas->paudKelasPesertaLurings()->count(),
            $kelas->jml_pengajar,
            $diklat->paudInstansi->ratio_pengajar_tambahan,
            $params['akun_id'],

            fn($kPetugasPaud) => $kelas
                ->paudKelasPetugasLurings()
                ->where('k_petugas_paud', '=', $kPetugasPaud)
                ->count(),

            fn() => app(PetugasService::class)
                ->queryKandidat(
                    $diklat->instansi_id,
                    $diklat->k_kota,
                    $params['k_petugas_paud'],
                    kelasLuringId: $kelas->paud_kelas_luring_id,
                    tglMulai: $diklat->tgl_mulai,
                    tglSelesai: $diklat->tgl_selesai,
                ),
        );

        /** @var PaudPetugas $petugas */
        foreach ($petugases as $petugas) {
            $kelasPetugas = new PaudKelasPetugasLuring();
            $kelasPetugas->fill($params);
            $kelasPetugas->paud_petugas_id      = $petugas->paud_petugas_id;
            $kelasPetugas->paud_kelas_luring_id = $kelas->paud_kelas_luring_id;
            $kelasPetugas->tahun                = $kelas->tahun;
            $kelasPetugas->angkatan             = $kelas->angkatan;
            $kelasPetugas->akun_id              = $petugas->akun_id;
            $kelasPetugas->k_konfirmasi_paud    = MKonfirmasiPaud::BELUM_KONFIRMASI;
            $kelasPetugas->created_by           = akunId();

            if (!$kelasPetugas->save()) {
                throw new FlowException("Petugas tidak berhasil disimpan");
            }
        }

        return $petugases->load('akun:akun_id,nama,email');
    }

    /**
     * @throws FlowException
     */
    public function ajuan(PaudDiklatLuring $diklat, PaudKelasLuring $kelas): PaudKelasLuring
    {
        $this->validateKelas($diklat, $kelas);
        $this->validateKelasBaru($kelas);

        $tidakBersedia = $kelas
            ->paudKelasPetugasLurings()
            ->whereNotIn('k_petugas_paud', [MPetugasPaud::ADMIN_KELAS])
            ->whereNotIn('k_konfirmasi_paud', [MKonfirmasiPaud::BERSEDIA])
            ->exists();

        if ($tidakBersedia) {
            throw new FlowException("Masih ada petugas yang belum bersedia/belum konfirmasi");
        }

        $tidakBersedia = $kelas
            ->paudKelasPesertaLurings()
            ->whereNotIn('k_konfirmasi_paud', [MKonfirmasiPaud::BERSEDIA])
            ->exists();

        if ($tidakBersedia) {
            throw new FlowException("Masih ada peserta yang belum bersedia/belum konfirmasi");
        }

        $jmlPetugases = $kelas->paudKelasPetugasLurings()
            ->groupBy('k_petugas_paud')
            ->get(['k_petugas_paud', DB::raw('count(1) jumlah')])
            ->pluck('jumlah', 'k_petugas_paud');

        $jmlPeserta = $kelas->paudKelasPesertaLurings()->count();

        app(PetugasService::class)->validatePetugasKelas(
            $jmlPeserta,
            $kelas->jml_pengajar,
            $diklat->paudInstansi->ratio_pengajar_tambahan,
            $jmlPetugases->all(),
        );

        if ($jmlPeserta < 20) {
            throw new FlowException("Jumlah peserta minimal 20 orang");
        }

        if ($jmlPeserta > 40) {
            throw new FlowException("Jumlah peserta maksimal 40 orang");
        }

        $kelas->wkt_ajuan     = Carbon::now();
        $kelas->k_verval_paud = MVervalPaud::DIAJUKAN;

        if (!$kelas->save()) {
            throw new FlowException('Proses ajuan tidak berhasil');
        }

        return $kelas;
    }

    /**
     * @throws FlowException
     */
    public function batalAjuan(PaudDiklatLuring $diklat, PaudKelasLuring $kelas): PaudKelasLuring
    {
        $this->validateKelas($diklat, $kelas);
        $this->validateKelasAjuan($kelas);

        $kelas->wkt_ajuan     = null;
        $kelas->k_verval_paud = MVervalPaud::KANDIDAT;

        if (!$kelas->save()) {
            throw new FlowException('Proses ajuan tidak berhasil');
        }

        return $kelas;
    }

    /**
     * @throws FlowException
     */
    public function verval(Akun $akun, PaudKelasLuring $kelas, array $params): PaudKelasLuring
    {
        if (in_array($kelas->k_verval_paud, [MVervalPaud::DITOLAK, MVervalPaud::REVISI, MVervalPaud::DISETUJUI])) {
            throw new FlowException('Kelas sudah diverval');
        }

        if ($kelas->k_verval_paud != MVervalPaud::DIAJUKAN) {
            throw new FlowException('Kelas belum diajukan');
        }

        $kelas->k_verval_paud  = $params['k_verval_paud'];
        $kelas->wkt_verval     = Carbon::now();
        $kelas->akun_id_verval = $akun->akun_id;
        $kelas->alasan         = $params['alasan'] ?? null;

        if (!$kelas->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $kelas;
    }

    /**
     * @throws FlowException
     */
    public function batalVerval(Akun $akun, PaudKelasLuring $kelas): PaudKelasLuring
    {
        if ($kelas->k_verval_paud == MVervalPaud::DIAJUKAN) {
            throw new FlowException('Kelas masih diajukan');
        }

        if (!in_array($kelas->k_verval_paud, [MVervalPaud::DITOLAK, MVervalPaud::REVISI, MVervalPaud::DISETUJUI])) {
            throw new FlowException('Kelas belum diverval');
        }

        $kelas->k_verval_paud  = MVervalPaud::DIAJUKAN;
        $kelas->wkt_verval     = Carbon::now();
        $kelas->akun_id_verval = $akun->akun_id;
        $kelas->alasan         = null;

        if (!$kelas->save()) {
            throw new FlowException('Proses simpan status verval tidak berhasil');
        }

        return $kelas;
    }

    /**
     * @throws FlowException
     * @throws Exception
     */
    public function uploadJadwal(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, UploadedFile $file)
    {
        $filename = app(KelasJadwalService::class)->uploadJadwal($diklat->instansi_id, $kelas->paud_diklat_luring_id, $kelas->file_jadwal, $file);

        $kelas->file_jadwal = $filename;
        $kelas->save();

        return $kelas;
    }
}
