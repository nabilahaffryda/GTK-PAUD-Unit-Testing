<?php

namespace App\Services\Instansi;

use App\Exceptions\FlowException;
use App\Models\Akun;
use App\Models\Instansi;
use App\Models\MBerkasLpdPaud;
use App\Models\MGroup;
use App\Models\MJenisInstansi;
use App\Models\MVervalPaud;
use App\Models\PaudAdmin;
use App\Models\PaudInstansi;
use App\Models\PaudInstansiBerkas;
use Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

    public function getOperatorLpd(Akun $akun, Instansi $instansi)
    {
        $operator = PaudAdmin::whereAkunId($akun->akun_id)
            ->where('k_group', MGroup::OP_LPD_DIKLAT_PAUD)->first();

        return $operator;
    }

    public function getPaudInstansi(Instansi $instansi, PaudAdmin $paudAdmin)
    {
        if($paudAdmin->instansi_id != $instansi->instansi_id) {
            abort(404);;
        }

        return PaudInstansi::whereInstansiId($instansi->instansi_id)
            ->where('angkatan', config('paud.angkatan'))
            ->where('tahun', config('paud.tahun'))->first();
    }

    public function getStatusLengkap(PaudInstansi $paudInstansi)
    {
        $instansi = $paudInstansi->instansi;

        $diklat = json_decode($paudInstansi->diklat, true);

        $isLengkapProfil = $instansi->nama && $instansi->no_telpon && $instansi->email && $instansi->alamat &&
            $instansi->k_propinsi && $instansi->k_kota && $paudInstansi->nama_penanggung_jawab &&
            $paudInstansi->nama_sekretaris && $paudInstansi->nama_bendahara && $paudInstansi->telp_penanggung_jawab &&
            $paudInstansi->telp_sekretaris && $paudInstansi->telp_bendahara && (count($diklat) >= 1);

        $isLengkapBerkas = $paudInstansi->paudInstansiBerkases->count() == 7;

        return [
            'profil' => $isLengkapProfil,
            'berkas' => $isLengkapBerkas,
        ];
    }
}
