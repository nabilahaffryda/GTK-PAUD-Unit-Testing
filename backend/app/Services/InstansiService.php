<?php

namespace App\Services;

use App\Models\Instansi;
use App\Models\MJenisInstansi;
use App\Models\MVervalPaud;
use App\Models\PaudInstansi;

class InstansiService
{
    public function create(Instansi $instansi, PaudInstansi $paudInstansi)
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

        $instansi->instansi_id      = $instansiId;
        $instansi->k_jenis_instansi = MJenisInstansi::LPD_PAUD;

        $paudInstansi->instansi_id           = $instansiId;
        $paudInstansi->tahun                 = config('paud.tahun');
        $paudInstansi->angkatan              = config('paud.angkatan');
        $paudInstansi->k_verval_paud         = MVervalPaud::KANDIDAT;

        $instansi->save();
        $paudInstansi->save();
    }
}
