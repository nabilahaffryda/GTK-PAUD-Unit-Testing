<?php

namespace App\Services\Instansi;

use App\Models\Instansi;
use App\Models\MJenisInstansi;
use App\Models\MVervalPaud;
use App\Models\PaudInstansi;
use Arr;
use Illuminate\Database\Eloquent\Builder;

class LpdService
{
    /**
     * @param array $params
     * @return Builder
     */
    public function query($params = [])
    {
        $query = PaudInstansi::query()
            ->join('instansi', 'paud_instansi.instansi_id', '=', 'instansi.instansi_id')
            ->select(['paud_instansi.*'])
            ->where([
                'instansi.k_jenis_instansi' => MJenisInstansi::LPD_PAUD,
                'paud_instansi.tahun'       => $params['tahun'] ?? config('paud.tahun'),
                'paud_instansi.angkatan'    => $params['angkatan'] ?? config('paud.angkatan'),
            ])
            ->with([
                'instansi.mPropinsi',
                'instansi.mKota',
            ]);

        if ($keyword = Arr::get($params, 'keyword')) {
            $query->where(function ($query) use ($keyword) {
                $query->where('instansi.nama', 'like', '%' . $keyword . '%');
            });
        }

        return $query;
    }

    /**
     * @param array $data
     * @return PaudInstansi
     */
    public function create(array $data)
    {
        $prefix  = 72;
        $maxKode = (int)"{$prefix}9999";
        do {
            $instansiId = Instansi::where('instansi_id', 'like', "{$prefix}%")->max('instansi_id') ?: (int)"{$prefix}0000";
            $instansiId++;

            if ($instansiId <= $maxKode) {
                break;
            }

            $prefix++;
            $maxKode = (int)"{$prefix}9999";
        } while (true);

        $instansi     = new Instansi($data);
        $paudInstansi = new PaudInstansi($data);

        $instansi->instansi_id      = $instansiId;
        $instansi->k_jenis_instansi = MJenisInstansi::LPD_PAUD;

        $paudInstansi->instansi_id   = $instansiId;
        $paudInstansi->tahun         = config('paud.tahun');
        $paudInstansi->angkatan      = config('paud.angkatan');
        $paudInstansi->k_verval_paud = MVervalPaud::KANDIDAT;

        $instansi->save();
        $paudInstansi->save();

        return $paudInstansi;
    }

    /**
     * @param PaudInstansi $paudInstansi
     * @return PaudInstansi
     */
    public function fetch(PaudInstansi $paudInstansi)
    {
        $paudInstansi->instansi
            ->load([
                'mPropinsi',
                'mKota',
            ]);

        return $paudInstansi;
    }

}
