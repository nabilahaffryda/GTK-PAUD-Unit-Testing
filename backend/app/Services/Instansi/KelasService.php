<?php


namespace App\Services\Instansi;


use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\Akun;
use App\Models\MKonfirmasiPaud;
use App\Models\MPetugasPaud;
use App\Models\MVervalPaud;
use App\Models\PaudDiklat;
use App\Models\PaudKelas;
use App\Models\PaudKelasPeserta;
use App\Models\PaudKelasPetugas;
use App\Models\PaudPetugas;
use Arr;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class KelasService
{
    public function index(PaudDiklat $paudDiklat, array $params): Builder
    {
        $query = PaudKelas::query()
            ->where('paud_kelas.paud_diklat_id', $paudDiklat->paud_diklat_id)
            ->with(['mKelurahan', 'mKecamatan']);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where('paud_kelas.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    public function create(PaudDiklat $paudDiklat, array $params): PaudKelas
    {
        $kelas                 = new PaudKelas($params);
        $kelas->paud_diklat_id = $paudDiklat->paud_diklat_id;
        $kelas->k_verval_paud  = MVervalPaud::KANDIDAT;
        $kelas->created_by     = akunId();

        if (!$kelas->save()) {
            throw new SaveException("Proses Tambah Kelas Tidak berhasil");
        }

        return $kelas->load(['mVervalPaud', 'paudDiklat', 'paudMapelKelas']);
    }

    public function update(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params): PaudKelas
    {
        $this->validateKelas($paudDiklat, $kelas);

        $kelas->fill($params);
        $kelas->updated_by = akunId();
        if (!$kelas->save()) {
            throw new SaveException("Proses simpan data kelas tida berhasil");
        }

        return $kelas;
    }

    public function fetch(PaudDiklat $paudDiklat, PaudKelas $kelas): PaudKelas
    {
        $this->validateKelas($paudDiklat, $kelas);

        return $kelas->load(['mVervalPaud', 'paudDiklat', 'paudMapelKelas']);
    }

    public function validateKelas(PaudDiklat $diklat, PaudKelas $kelas)
    {
        if ($kelas->paud_diklat_id <> $diklat->paud_diklat_id) {
            throw new FlowException('Kelas tidak ditemukan');
        }
    }

    public function indexPeserta(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params): Builder
    {
        $this->validateKelas($paudDiklat, $kelas);

        $query = PaudKelasPeserta::query()
            ->where('paud_kelas_peserta.paud_kelas_id', '=', $kelas->paud_kelas_id)
            ->with(['ptk:ptk_id,nama,email']);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->join('ptk', 'ptk.ptk_id', '=', 'paud_kelas_peserta.ptk_id')
                ->where('ptk.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    public function indexPetugas(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params): Builder
    {
        $this->validateKelas($paudDiklat, $kelas);

        $query = PaudKelasPetugas::query()
            ->where('paud_kelas_petugas.paud_kelas_id', '=', $kelas->paud_kelas_id)
            ->where('paud_kelas_petugas.k_petugas_paud', '=', $params['k_petugas_paud'])
            ->with(['akun:akun_id,nama,email', 'mKonfirmasiPaud']);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->join('akun', 'akun.akun_id', '=', 'paud_kelas_petugas.akun_id')
                ->where('akun.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    public function indexPetugasKandidat(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params)
    {
        $this->validateKelas($paudDiklat, $kelas);

        $query = PaudPetugas::query()
            ->when($params['k_petugas_paud'] != MPetugasPaud::PENGAJAR, function ($query) use ($paudDiklat) {
                $query->where('paud_petugas.instansi_id', '=', $paudDiklat->instansi_id);
            })
            ->where('paud_petugas.k_petugas_paud', '=', $params['k_petugas_paud'])
            ->whereNotExists(function ($query) use ($params) {
                $query->select(DB::raw(1))
                    ->from('paud_kelas_petugas')
                    ->where('paud_kelas_petugas.k_petugas_paud', '=', $params['k_petugas_paud'])
                    ->whereRaw('paud_petugas.paud_petugas_id = paud_kelas_petugas.paud_petugas_id');
            })
            ->with(['akun:akun_id,nama,email']);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->join('akun', 'akun.akun_id', '=', 'paud_kelas_petugas.akun_id')
                ->where('akun.nama', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    /**
     * @throws FlowException
     */
    public function createPetugas(PaudDiklat $paudDiklat, PaudKelas $kelas, array $params): Collection
    {
        $this->validateKelas($paudDiklat, $kelas);

        $jmlPetugas = PaudKelasPetugas::query()
            ->where('paud_kelas_petugas.k_petugas_paud', '=', $params['k_petugas_paud'])
            ->count();

        $batasan = [
            MPetugasPaud::PENGAJAR           => 9,
            MPetugasPaud::PENGAJAR_TAMBAHAN  => 4,
            MPetugasPaud::PEMBIMBING_PRAKTIK => 4,
            MPetugasPaud::ADMIN_KELAS        => 1,
        ];

        if (isset($batasan[$params['k_petugas_paud']]) && $jmlPetugas >= $batasan[$params['k_petugas_paud']]) {
            throw new FlowException("Jumlah petugas maksimal adalah {$batasan[$params['k_petugas_paud']]} orang");
        }

        $paudPetugases = PaudPetugas::whereIn('akun_id', $params['akun_id'])
            ->when($params['k_petugas_paud'] != MPetugasPaud::PENGAJAR, function ($query) use ($paudDiklat) {
                $query->where('paud_petugas.instansi_id', '=', $paudDiklat->instansi_id);
            })
            ->where('paud_petugas.k_petugas_paud', '=', $params['k_petugas_paud'])
            ->whereNotExists(function ($query) use ($params) {
                $query->select(DB::raw(1))
                    ->from('paud_kelas_petugas')
                    ->where('paud_kelas_petugas.k_petugas_paud', '=', $params['k_petugas_paud'])
                    ->whereRaw('paud_petugas.paud_petugas_id = paud_kelas_petugas.paud_petugas_id');
            })
            ->get();

        if ($diff = array_diff($params['akun_id'], $paudPetugases->pluck('akun_id')->all())) {
            $akuns = Akun::whereIn('akun_id', $diff)->pluck('email')->unique()->all();
            $namas = implode(', ', $akuns);

            throw new FlowException("Petugas dengan email {$namas} tidak ditemukan");
        }

        /** @var PaudPetugas $petugas */
        foreach ($paudPetugases as $petugas) {
            $paudKelasPetugas = new PaudKelasPetugas();
            $paudKelasPetugas->fill($params);
            $paudKelasPetugas->paud_petugas_id   = $petugas->paud_petugas_id;
            $paudKelasPetugas->paud_kelas_id     = $kelas->paud_kelas_id;
            $paudKelasPetugas->akun_id           = $petugas->akun_id;
            $paudKelasPetugas->k_konfirmasi_paud = MKonfirmasiPaud::BELUM_KONFIRMASI;
            $paudKelasPetugas->created_by        = akunId();

            if (!$paudKelasPetugas->save()) {
                throw new FlowException("Petugas tidak berhasil disimpan");
            }
        }

        return $paudPetugases->load('akun:akun_id,nama,email');
    }

    /**
     * @throws FlowException
     */
    public function ajuan(PaudDiklat $paudDiklat, PaudKelas $kelas): PaudKelas
    {
        $this->validateKelas($paudDiklat, $kelas);

        $jmlPetugases = PaudKelasPetugas::query()
            ->groupBy('k_petugas_paud')
            ->get(['k_petugas_paud', DB::raw('count(1) jumlah')])
            ->keyBy('k_petugas_paud');

        $batasan = [
            MPetugasPaud::PENGAJAR           => 3,
            MPetugasPaud::PENGAJAR_TAMBAHAN  => 2,
            MPetugasPaud::PEMBIMBING_PRAKTIK => 2,
            MPetugasPaud::ADMIN_KELAS        => 1,
        ];

        foreach ($batasan as $kPetugasPaud => $jmlMin) {
            if ($jmlPetugases->get($kPetugasPaud, 0) < $jmlMin) {
                $mPetugasPaud = MPetugasPaud::find($kPetugasPaud);
                throw new FlowException("Petugas {$mPetugasPaud->keterangan} minimal {$jmlMin} orang");
            }
        }

        $kelas->wkt_ajuan     = Carbon::now();
        $kelas->k_verval_paud = MVervalPaud::DIAJUKAN;

        if (!$kelas->save()) {
            throw new FlowException('Proses ajuan tidak berhasil');
        }

        return $kelas;
    }

    public function batalAjuan(PaudDiklat $paudDiklat, PaudKelas $kelas): PaudKelas
    {
        $this->validateKelas($paudDiklat, $kelas);

        $kelas->wkt_ajuan     = null;
        $kelas->k_verval_paud = MVervalPaud::KANDIDAT;

        if (!$kelas->save()) {
            throw new FlowException('Proses ajuan tidak berhasil');
        }

        return $kelas;
    }
}
