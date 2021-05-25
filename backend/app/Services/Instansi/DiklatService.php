<?php


namespace App\Services\Instansi;


use App\Exceptions\SaveException;
use App\Models\Instansi;
use App\Models\PaudDiklat;
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
        $paudDiklat              = new PaudDiklat($params);
        $paudDiklat->instansi_id = $instansi->instansi_id;

        $paudDiklat->save();

        return $paudDiklat;
    }

    public function fetch(PaudDiklat $paudDiklat)
    {
        $paudDiklat->load([
            'instansi',
            'mPropinsi',
            'mKota',
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

        $paudDiklat->delete();

        return $instansi;
    }
}
