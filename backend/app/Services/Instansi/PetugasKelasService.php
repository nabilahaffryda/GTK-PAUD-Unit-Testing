<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Models\MKonfirmasiPaud;
use App\Models\MPetugasPaud;
use App\Models\MVervalPaud;
use App\Models\PaudKelas;
use App\Models\PaudKelasPetugas;
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

    /**
     * @throws FlowException
     */
    public function konfirmasiBersedia(PaudKelasPetugas $kelasPetugas): PaudKelasPetugas
    {
        $this->validateBelumKonfirmasi($kelasPetugas);
        $this->validateVervalKelasBaru($kelasPetugas);

        $kelasPetugas->k_konfirmasi_paud = MKonfirmasiPaud::BERSEDIA;
        $kelasPetugas->save();

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
