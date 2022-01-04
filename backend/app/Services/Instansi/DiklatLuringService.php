<?php


namespace App\Services\Instansi;


use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Models\MKota;
use App\Models\MLpdPaud;
use App\Models\PaudDiklatLuring;
use App\Models\PaudInstansi;
use Arr;
use Carbon\Carbon;

class DiklatLuringService
{

    public function index(PaudInstansi $instansi, array $params)
    {
        $query = PaudDiklatLuring::query()
            ->where('paud_instansi_id', '=', $instansi->paud_instansi_id)
            ->with([
                'mDiklatPaud',
                'mJenjangDiklatPaud',
            ]);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where(function ($query) use ($keyword) {
                $query->where('paud_diklat_luring.nama', 'like', '%' . $keyword . '%');
            });
        }

        return $query;
    }

    /**
     * @throws SaveException
     */
    public function create(PaudInstansi $paudInstansi, array $params)
    {
        $diklat = new PaudDiklatLuring($params);

        $diklat->tahun            = $paudInstansi->tahun;
        $diklat->angkatan         = $paudInstansi->angkatan;
        $diklat->instansi_id      = $paudInstansi->instansi_id;
        $diklat->paud_instansi_id = $paudInstansi->paud_instansi_id;

        switch ($paudInstansi->k_lpd_paud) {
            default:
            case MLpdPaud::LPD_KOTA_KABUPATEN:
                $diklat->k_kota = $paudInstansi->instansi->k_kota;

            case MLpdPaud::LPD_PROVINSI:
                $diklat->k_propinsi = $paudInstansi->instansi->k_propinsi;

            case MLpdPaud::LPD_PUSAT:
        }

        $mKota = MKota::findOrFail($diklat->k_kota);
        if ($mKota->k_propinsi != $diklat->k_propinsi) {
            throw new SaveException('Kota dan propinsi yang dipilih tidak sesuai');
        }

        $diklat->save();

        return $diklat;
    }

    public function fetch(PaudDiklatLuring $diklat)
    {
        $diklat->load([
            'mPropinsi',
            'mKota',
            'mDiklatPaud',
            'mJenjangDiklatPaud',
        ]);

        return $diklat;
    }

    /**
     * @throws SaveException
     */
    public function update(PaudDiklatLuring $diklat, array $params)
    {
        $diklat->fill($params);

        if (!$diklat->save()) {
            throw new SaveException('Proses simpan data diklat tidak berhasil');
        }

        return $diklat->load([
            'mPropinsi',
            'mKota',
            'mDiklatPaud',
            'mJenjangDiklatPaud',
        ]);
    }

    /**
     * @throws SaveException
     */
    public function delete(PaudDiklatLuring $diklat)
    {
        if ($diklat->paudKelasLurings()->exists()) {
            throw new SaveException('Diklat tidak bisa dihapus karena sudah mempunyai kelas');
        }

        $diklat->delete();

        return $diklat;
    }

    /**
     * @throws FlowException
     */
    public function validateSelesai(PaudDiklatLuring $diklat)
    {
        if ($diklat->tgl_selesai && $diklat->tgl_selesai->isAfter(Carbon::now()->endOfDay())) {
            throw new FlowException('Diklat akan berakhir pada ' . $diklat->tgl_selesai->toDateString());
        }
    }
}
