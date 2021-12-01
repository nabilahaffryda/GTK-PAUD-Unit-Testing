<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Models\MKonfirmasiPaud;
use App\Models\MPetugasPaud;
use App\Models\MVervalPaud;
use App\Models\PaudKelas;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPetugas;
use App\Models\PaudPetugas;
use Illuminate\Database\Eloquent\Builder;

class PetugasKelasService
{
    /**
     * @throws FlowException
     */
    public function validateVervalKelasBaru(PaudKelasPetugas $kelasPetugas)
    {
        $kelas = $kelasPetugas->paudKelas;
        if ($kelas->k_verval_paud != MVervalPaud::KANDIDAT) {
            throw new FlowException('Konfirmasi sudah dikunci karena kelas sudah diajukan/diproses');
        }
    }

    /**
     * @throws FlowException
     */
    public function validateBelumKonfirmasi(PaudKelasPetugas $kelasPetugas)
    {
        if ($kelasPetugas->k_konfirmasi_paud != MKonfirmasiPaud::BELUM_KONFIRMASI) {
            throw new FlowException('Anda telah melakukan konfirmasi, silakan dibatalkan terlebih dahulu');
        }
    }

    /**
     * @throws FlowException
     */
    public function validateSudahKonfirmasi(PaudKelasPetugas $kelasPetugas)
    {
        if (!in_array($kelasPetugas->k_konfirmasi_paud, [MKonfirmasiPaud::BERSEDIA, MKonfirmasiPaud::TIDAK_BERSEDIA])) {
            throw new FlowException('Anda belum melakukan konfirmasi');
        }
    }

    /**
     * @throws FlowException
     */
    public function validateSatuKonfirmasi(PaudKelasPetugas $kelasPetugas)
    {
        $periode = $kelasPetugas->paudKelas->paudDiklat->paudPeriode;

        /** @var PaudKelas $kelas */
        $kelas = PaudKelas::query()
            ->join('paud_diklat', 'paud_diklat.paud_diklat_id', '=', 'paud_kelas.paud_diklat_id')
            ->where('paud_diklat.paud_periode_id', '=', $periode->paud_periode_id)
            ->whereHas('paudKelasPetugases', function (Builder $query) use ($kelasPetugas) {
                $query
                    ->where('paud_kelas_petugas.paud_petugas_id', '=', $kelasPetugas->paud_petugas_id)
                    ->where('paud_kelas_petugas.k_konfirmasi_paud', '=', MKonfirmasiPaud::BERSEDIA)
                    ->when($kelasPetugas->k_petugas_paud == MPetugasPaud::ADMIN_KELAS, function ($query) use ($kelasPetugas) {
                        $query->where('paud_kelas_petugas.k_petugas_paud', '=', MPetugasPaud::ADMIN_KELAS)
                            ->where('paud_kelas_petugas.paud_kelas_id', '=', $kelasPetugas->paud_kelas_id);
                    }, function ($query) {
                        $query->where('paud_kelas_petugas.k_petugas_paud', '<>', MPetugasPaud::ADMIN_KELAS);
                    });
            })
            ->first();

        if ($kelas) {
            throw new FlowException('Anda telah mengkonfirmasi kelas ' . $kelas->nama);
        }

        /** @var PaudKelasLuring $kelas */
        $kelas = PaudKelasLuring::query()
            ->join('paud_diklat_luring', 'paud_diklat_luring.paud_diklat_luring_id', '=', 'paud_diklat_luring.paud_diklat_luring_id')
            ->where('paud_diklat_luring.tgl_mulai', '<=', $periode->tgl_diklat_selesai)
            ->where('paud_diklat_luring.tgl_selesai', '>=', $periode->tgl_diklat_mulai)
            ->whereHas('paudKelasPetugasLurings', function (Builder $query) use ($kelasPetugas) {
                $query
                    ->where('paud_kelas_petugas_luring.paud_petugas_id', '=', $kelasPetugas->paud_petugas_id)
                    ->where('paud_kelas_petugas_luring.k_konfirmasi_paud', '=', MKonfirmasiPaud::BERSEDIA);
            })
            ->first();

        if ($kelas) {
            throw new FlowException('Anda telah mengkonfirmasi kelas luring ' . $kelas->nama);
        }
    }

    public function listKonfirmasiKesediaan(int $akunId): Builder
    {
        return PaudKelasPetugas::query()
            ->join('paud_kelas', 'paud_kelas.paud_kelas_id', '=', 'paud_kelas_petugas.paud_kelas_id')
            ->where('paud_kelas.tahun', config('paud.tahun'))
            ->where('paud_kelas.angkatan', config('paud.angkatan'))
            ->where('paud_kelas.k_verval_paud', '=', MVervalPaud::KANDIDAT)
            ->where('paud_kelas_petugas.akun_id', '=', $akunId);
    }

    public function listKelas(int $akunId): Builder
    {
        return PaudKelas::query()
            ->whereHas('paudKelasPetugases', function ($query) use ($akunId) {
                $query
                    ->join('paud_petugas', 'paud_petugas.paud_petugas_id', '=', 'paud_kelas_petugas.paud_petugas_id')
                    ->where('paud_petugas.akun_id', '=', $akunId)
                    ->where(function ($query) {
                        $query->orWhere([
                            'paud_kelas_petugas.k_konfirmasi_paud' => MKonfirmasiPaud::BERSEDIA,
                            'paud_kelas_petugas.k_petugas_paud'    => MPetugasPaud::ADMIN_KELAS,
                        ]);
                    });
            })
            ->when($args['keyword'] ?? null, function ($query, $value) {
                $query->where('nama', 'like', '%' . $value . '%');
            });
    }

    public function setTidakBersedia(int $periodeId, PaudPetugas $petugas, PaudKelasPetugas $kelasPetugas = null)
    {
        $kelasPetugases = PaudKelasPetugas::query()
            ->join('paud_kelas', 'paud_kelas.paud_kelas_id', '=', 'paud_kelas_petugas.paud_kelas_id')
            ->join('paud_diklat', 'paud_diklat.paud_diklat_id', '=', 'paud_kelas.paud_diklat_id')
            ->where('paud_diklat.paud_periode_id', '=', $periodeId)
            ->where('paud_kelas_petugas.paud_petugas_id', '=', $petugas->paud_petugas_id)
            ->where('paud_kelas_petugas.k_konfirmasi_paud', '=', MKonfirmasiPaud::BELUM_KONFIRMASI)
            ->when($kelasPetugas, function ($query) use ($kelasPetugas) {
                $query->when($kelasPetugas->k_petugas_paud == MPetugasPaud::ADMIN_KELAS, function ($query) use ($kelasPetugas) {
                    $query->where('paud_kelas_petugas.k_petugas_paud', '=', MPetugasPaud::ADMIN_KELAS)
                        ->where('paud_kelas_petugas.paud_kelas_id', '=', $kelasPetugas->paud_kelas_id);
                }, function ($query) {
                    $query->where('paud_kelas_petugas.k_petugas_paud', '<>', MPetugasPaud::ADMIN_KELAS);
                });
            })
            ->get('paud_kelas_petugas.*');

        foreach ($kelasPetugases as $kelasPetugasOther) {
            $kelasPetugasOther->k_konfirmasi_paud = MKonfirmasiPaud::TIDAK_BERSEDIA;
            $kelasPetugasOther->save();
        }
    }

    /**
     * @throws FlowException
     */
    public function konfirmasiBersedia(PaudKelasPetugas $kelasPetugas): PaudKelasPetugas
    {
        $this->validateBelumKonfirmasi($kelasPetugas);
        $this->validateVervalKelasBaru($kelasPetugas);
        $this->validateSatuKonfirmasi($kelasPetugas);

        $kelasPetugas->k_konfirmasi_paud = MKonfirmasiPaud::BERSEDIA;
        $kelasPetugas->save();

        $periode = $kelasPetugas->paudKelas->paudDiklat->paudPeriode;
        $this->setTidakBersedia($periode->paud_periode_id, $kelasPetugas->paudPetugas, $kelasPetugas);

        app(PetugasKelasLuringService::class)->setTidakBersedia($periode->tgl_diklat_mulai, $periode->tgl_diklat_selesai, $kelasPetugas->paudPetugas);

        return $kelasPetugas;
    }

    /**
     * @throws FlowException
     */
    public function konfirmasiTidakBersedia(PaudKelasPetugas $kelasPetugas): PaudKelasPetugas
    {
        $this->validateBelumKonfirmasi($kelasPetugas);
        $this->validateVervalKelasBaru($kelasPetugas);

        $kelasPetugas->k_konfirmasi_paud = MKonfirmasiPaud::TIDAK_BERSEDIA;
        $kelasPetugas->save();

        return $kelasPetugas;
    }

    /**
     * @throws FlowException
     */
    public function resetKonfirmasi(PaudKelasPetugas $kelasPetugas): PaudKelasPetugas
    {
        $this->validateSudahKonfirmasi($kelasPetugas);
        $this->validateVervalKelasBaru($kelasPetugas);

        $kelasPetugas->k_konfirmasi_paud = MKonfirmasiPaud::BELUM_KONFIRMASI;
        $kelasPetugas->save();

        return $kelasPetugas;
    }
}
