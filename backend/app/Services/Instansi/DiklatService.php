<?php


namespace App\Services\Instansi;


use App\Exceptions\SaveException;
use App\Models\Instansi;
use App\Models\MKota;
use App\Models\MLpdPaud;
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

    /**
     * @throws SaveException
     */
    public function create(Instansi $instansi, array $params)
    {
        /** @var PaudInstansi $paudInstansi */
        $paudInstansi = PaudInstansi::where([
            'instansi_id' => $instansi->instansi_id,
            'tahun'       => config('paud.tahun'),
            'angkatan'    => config('paud.angkatan'),
        ])->firstOrFail();

        $paudDiklat                   = new PaudDiklat($params);
        $paudDiklat->tahun            = config('paud.tahun');
        $paudDiklat->angkatan         = config('paud.angkatan');
        $paudDiklat->instansi_id      = $instansi->instansi_id;
        $paudDiklat->paud_instansi_id = $paudInstansi->paud_instansi_id;

        switch ($paudInstansi->k_lpd_paud) {
            default:
            case MLpdPaud::LPD_KOTA_KABUPATEN:
                $paudDiklat->k_kota = $instansi->k_kota;
            case MLpdPaud::LPD_PROVINSI:
                $paudDiklat->k_propinsi = $instansi->k_propinsi;
            case MLpdPaud::LPD_PUSAT:
        }

        $mKota = MKota::findOrFail($paudDiklat->k_kota);
        if ($mKota->k_propinsi != $paudDiklat->k_propinsi) {
            throw new SaveException('Kota dan propinsi yang dipilih tidak sesuai');
        }

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

    /**
     * @throws SaveException
     */
    public function delete(PaudDiklat $paudDiklat)
    {
        $instansi = $paudDiklat->instansi;

        if ($paudDiklat->paudKelases()->exists()) {
            throw new SaveException('Diklat tidak bisa dihapus karena sudah mempunyai kelas');
        }

        $paudDiklat->delete();

        return $instansi;
    }
}
