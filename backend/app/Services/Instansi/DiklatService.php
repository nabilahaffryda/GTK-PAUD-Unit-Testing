<?php


namespace App\Services\Instansi;


use App\Exceptions\SaveException;
use App\Models\Instansi;
use App\Models\MVervalPaud;
use App\Models\PaudDiklat;
use App\Models\PaudInstansi;
use Arr;

class DiklatService
{

    public function index(Instansi $instansi, array $params)
    {
        $query = PaudDiklat::query()
            ->where('instansi_id', '=', $instansi->instansi_id)
            ->with([
                'paudPeriode',
            ]);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where(function ($query) use ($keyword) {
                $query->where('paud_diklat.nama', 'like', '%' . $keyword . '%');
            });
        }

        return $query;
    }

    public function create(Instansi $instansi, array $params)
    {
        $paudInstansi = PaudInstansi::where([
            'instansi_id' => $instansi->instansi_id,
            'tahun'       => config('paud.tahun'),
            'angkatan'    => config('paud.angkatan'),
        ])->first();

        $paudDiklat                   = new PaudDiklat($params);
        $paudDiklat->tahun            = config('paud.tahun');
        $paudDiklat->angkatan         = config('paud.angkatan');
        $paudDiklat->instansi_id      = $instansi->instansi_id;
        $paudDiklat->paud_instansi_id = $paudInstansi->paud_instansi_id;

        $paudDiklat->save();

        return $paudDiklat;
    }

    public function fetch(PaudDiklat $paudDiklat)
    {
        $paudDiklat->load([
            'instansi',
            'mPropinsi',
            'mKota',
            'paudPeriode',
        ]);

        return $paudDiklat;
    }

    public function update(?Instansi $instansi, PaudDiklat $paudDiklat, array $params)
    {
        $paudDiklat->fill($params);

        if (!$paudDiklat->save()) {
            throw new SaveException('Proses simpan data diklat tidak berhasil');
        }

        return $paudDiklat->load([
            'instansi',
            'mPropinsi',
            'mKota',
        ]);
    }

    public function delete(PaudDiklat $paudDiklat)
    {
        $instansi = $paudDiklat->instansi;

        foreach ($paudDiklat->paudKelases as $kelas) {
            if (in_array($kelas->k_verval_paud, [
                MVervalPaud::DIAJUKAN,
                MVervalPaud::DIPROSES,
                MVervalPaud::REVISI,
                MVervalPaud::DISETUJUI])
            ) {
                throw new SaveException('Sudah ada kelas yang diajukan, sedang diproses atau telah disetujui');
            }
        }

        foreach ($paudDiklat->paudKelases as $kelas) {
            foreach ($kelas->paudKelasPetugases as $petugas) {
                $petugas->delete();
            }

            foreach ($kelas->paudKelasPesertas as $peserta) {
                $peserta->delete();
            }

            $kelas->delete();
        }

        $paudDiklat->delete();

        return $instansi;
    }
}
