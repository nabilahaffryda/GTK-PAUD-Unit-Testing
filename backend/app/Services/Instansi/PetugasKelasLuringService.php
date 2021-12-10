<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Models\MKonfirmasiPaud;
use App\Models\MPetugasPaud;
use App\Models\MVervalPaud;
use App\Models\PaudKelas;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPetugasLuring;
use App\Models\PaudPetugas;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class PetugasKelasLuringService
{
    /**
     * @throws FlowException
     */
    public function validateVervalKelasBaru(PaudKelasPetugasLuring $kelasPetugas)
    {
        $kelas = $kelasPetugas->paudKelasLuring;
        if ($kelas->k_verval_paud != MVervalPaud::KANDIDAT) {
            throw new FlowException('Konfirmasi sudah dikunci karena kelas sudah diajukan/diproses');
        }
    }

    /**
     * @throws FlowException
     */
    public function validateBelumKonfirmasi(PaudKelasPetugasLuring $kelasPetugas)
    {
        if ($kelasPetugas->k_konfirmasi_paud != MKonfirmasiPaud::BELUM_KONFIRMASI) {
            throw new FlowException('Anda telah melakukan konfirmasi, silakan dibatalkan terlebih dahulu');
        }
    }

    /**
     * @throws FlowException
     */
    public function validateSudahKonfirmasi(PaudKelasPetugasLuring $kelasPetugas)
    {
        if (!in_array($kelasPetugas->k_konfirmasi_paud, [MKonfirmasiPaud::BERSEDIA, MKonfirmasiPaud::TIDAK_BERSEDIA])) {
            throw new FlowException('Anda belum melakukan konfirmasi');
        }
    }

    /**
     * @throws FlowException
     */
    public function validateSatuKonfirmasi(PaudKelasPetugasLuring $kelasPetugas)
    {
        $diklat = $kelasPetugas->paudKelasLuring->paudDiklatLuring;

        /** @var PaudKelasLuring $kelas */
        $kelas = PaudKelasLuring::query()
            ->join('paud_diklat_luring', 'paud_diklat_luring.paud_diklat_luring_id', '=', 'paud_diklat_luring.paud_diklat_luring_id')
            ->where('paud_diklat_luring.tgl_mulai', '<=', $diklat->tgl_selesai)
            ->where('paud_diklat_luring.tgl_selesai', '>=', $diklat->tgl_mulai)
            ->whereHas('paudKelasPetugasLurings', function (Builder $query) use ($kelasPetugas) {
                $query
                    ->where('paud_kelas_petugas_luring.paud_petugas_id', '=', $kelasPetugas->paud_petugas_id)
                    ->where('paud_kelas_petugas_luring.k_konfirmasi_paud', '=', MKonfirmasiPaud::BERSEDIA)
                    ->when($kelasPetugas->k_petugas_paud == MPetugasPaud::ADMIN_KELAS, function ($query) use ($kelasPetugas) {
                        $query->where('paud_kelas_petugas_luring.k_petugas_paud', '=', MPetugasPaud::ADMIN_KELAS)
                            ->where('paud_kelas_petugas_luring.paud_kelas_luring_id', '=', $kelasPetugas->paud_kelas_luring_id);
                    }, function ($query) {
                        $query->where('paud_kelas_petugas_luring.k_petugas_paud', '<>', MPetugasPaud::ADMIN_KELAS);
                    });
            })
            ->first();

        if ($kelas) {
            throw new FlowException('Anda telah mengkonfirmasi kelas luring ' . $kelas->nama);
        }

        $periodes = app(PeriodeService::class)->getRentang($diklat->tgl_mulai, $diklat->tgl_selesai);

        /** @var PaudKelas $kelas */
        $kelas = PaudKelas::query()
            ->join('paud_diklat', 'paud_diklat.paud_diklat_id', '=', 'paud_kelas.paud_diklat_id')
            ->whereIn('paud_diklat.paud_periode_id', $periodes->pluck('paud_periode_id'))
            ->whereHas('paudKelasPetugases', function (Builder $query) use ($kelasPetugas) {
                $query
                    ->where('paud_kelas_petugas.paud_petugas_id', '=', $kelasPetugas->paud_petugas_id)
                    ->where('paud_kelas_petugas.k_konfirmasi_paud', '=', MKonfirmasiPaud::BERSEDIA);
            })
            ->first();

        if ($kelas) {
            throw new FlowException('Anda telah mengkonfirmasi kelas daring ' . $kelas->nama);
        }
    }

    public function listKonfirmasiKesediaan(int $akunId): Builder
    {
        return PaudKelasPetugasLuring::query()
            ->join('paud_kelas_luring', 'paud_kelas_luring.paud_kelas_luring_id', '=', 'paud_kelas_petugas_luring.paud_kelas_luring_id')
            ->where('paud_kelas_luring.tahun', config('paud.tahun'))
            ->where('paud_kelas_luring.angkatan', config('paud.angkatan'))
            ->where('paud_kelas_luring.k_verval_paud', '=', MVervalPaud::KANDIDAT)
            ->where('paud_kelas_petugas_luring.akun_id', '=', $akunId);
    }

    public function listKelas(int $akunId): Builder
    {
        return PaudKelasLuring::query()
            ->whereHas('paudKelasPetugasLurings', function ($query) use ($akunId) {
                $query
                    ->join('paud_petugas', 'paud_petugas.paud_petugas_id', '=', 'paud_kelas_petugas_luring.paud_petugas_id')
                    ->where('paud_petugas.akun_id', '=', $akunId)
                    ->where(function ($query) {
                        $query->orWhere([
                            'paud_kelas_petugas_luring.k_konfirmasi_paud' => MKonfirmasiPaud::BERSEDIA,
                            'paud_kelas_petugas_luring.k_petugas_paud'    => MPetugasPaud::ADMIN_KELAS,
                        ]);
                    });
            })
            ->when($args['keyword'] ?? null, function ($query, $value) {
                $query->where('nama', 'like', '%' . $value . '%');
            });
    }

    public function setTidakBersedia(Carbon $tglMulai, Carbon $tglSelesai, PaudPetugas $petugas, PaudKelasPetugasLuring $kelasPetugas = null)
    {
        $kelasPetugases = PaudKelasPetugasLuring::query()
            ->join('paud_kelas_luring', 'paud_kelas_luring.paud_kelas_luring_id', '=', 'paud_kelas_petugas_luring.paud_kelas_luring_id')
            ->join('paud_diklat_luring', 'paud_diklat_luring.paud_diklat_luring_id', '=', 'paud_kelas_luring.paud_diklat_luring_id')
            ->where('paud_diklat_luring.tgl_mulai', '<=', $tglSelesai)
            ->where('paud_diklat_luring.tgl_selesai', '>=', $tglMulai)
            ->where('paud_kelas_petugas_luring.paud_petugas_id', '=', $petugas->paud_petugas_id)
            ->where('paud_kelas_petugas_luring.k_konfirmasi_paud', '=', MKonfirmasiPaud::BELUM_KONFIRMASI)
            ->when($kelasPetugas, function ($query) use ($kelasPetugas) {
                $query->when($kelasPetugas->k_petugas_paud == MPetugasPaud::ADMIN_KELAS, function ($query) use ($kelasPetugas) {
                    $query->where('paud_kelas_petugas_luring.k_petugas_paud', '=', MPetugasPaud::ADMIN_KELAS)
                        ->where('paud_kelas_petugas_luring.paud_kelas_luring_id', '=', $kelasPetugas->paud_kelas_luring_id);
                }, function ($query) {
                    $query->where('paud_kelas_petugas_luring.k_petugas_paud', '<>', MPetugasPaud::ADMIN_KELAS);
                });
            })
            ->get('paud_kelas_petugas_luring.*');

        foreach ($kelasPetugases as $kelasPetugasOther) {
            $kelasPetugasOther->k_konfirmasi_paud = MKonfirmasiPaud::TIDAK_BERSEDIA;
            $kelasPetugasOther->save();
        }
    }

    /**
     * @throws FlowException
     */
    public function konfirmasiBersedia(PaudKelasPetugasLuring $kelasPetugas): PaudKelasPetugasLuring
    {
        $this->validateBelumKonfirmasi($kelasPetugas);
        $this->validateVervalKelasBaru($kelasPetugas);
        $this->validateSatuKonfirmasi($kelasPetugas);

        $kelasPetugas->k_konfirmasi_paud = MKonfirmasiPaud::BERSEDIA;
        $kelasPetugas->save();

        $diklat = $kelasPetugas->paudKelasLuring->paudDiklatLuring;
        $this->setTidakBersedia($diklat->tgl_mulai, $diklat->tgl_selesai, $kelasPetugas->paudPetugas, $kelasPetugas);

        $periodes = app(PeriodeService::class)->getRentang($diklat->tgl_mulai, $diklat->tgl_selesai);
        foreach ($periodes as $periode) {
            app(PetugasKelasService::class)->setTidakBersedia($periode->paud_periode_id, $kelasPetugas->paudPetugas);
        }

        return $kelasPetugas;
    }

    /**
     * @throws FlowException
     */
    public function konfirmasiTidakBersedia(PaudKelasPetugasLuring $kelasPetugas): PaudKelasPetugasLuring
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
    public function resetKonfirmasi(PaudKelasPetugasLuring $kelasPetugas): PaudKelasPetugasLuring
    {
        $this->validateSudahKonfirmasi($kelasPetugas);
        $this->validateVervalKelasBaru($kelasPetugas);

        $kelasPetugas->k_konfirmasi_paud = MKonfirmasiPaud::BELUM_KONFIRMASI;
        $kelasPetugas->save();

        return $kelasPetugas;
    }
}
