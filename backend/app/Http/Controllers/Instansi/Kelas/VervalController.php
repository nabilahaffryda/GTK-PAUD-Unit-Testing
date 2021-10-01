<?php

namespace App\Http\Controllers\Instansi\Kelas;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MKonfirmasiPaud;
use App\Models\MVervalPaud;
use App\Models\PaudKelas;
use App\Services\Instansi\KelasService;
use Arr;
use Illuminate\Http\Request;

class VervalController extends Controller
{
    public function __construct(
        protected KelasService $service,
    )
    {
    }

    public function index(Request $request)
    {
        $params = $request->all();

        $q = PaudKelas::query()
            ->where([
                'paud_kelas.tahun'    => Arr::get($params, 'tahun', config('paud.tahun')),
                'paud_kelas.angkatan' => Arr::get($params, 'angkatan', config('paud.angkatan')),
            ])
            ->whereNotIn('paud_kelas.k_verval_paud', [MVervalPaud::KANDIDAT])
            ->with([
                'mVervalPaud',
                'paudDiklat.Instansi',
                'paudDiklat.paudInstansi',
                'paudDiklat.paudPeriode',
                'paudMapelKelas',
            ]);

        return BaseCollection::make($q->paginate((int)$request->get('count', 10)));
    }

    public function fetch(PaudKelas $kelas)
    {
        $kelas->load([
            'mVervalPaud',
            'paudDiklat.Instansi',
            'paudDiklat.paudInstansi',
            'paudDiklat.paudPeriode',
            'paudMapelKelas',
        ]);

        return BaseResource::make($kelas);
    }

    public function peserta(PaudKelas $kelas, Request $request)
    {
        $q = $kelas->paudKelasPesertas()
            ->with([
                'ptk',
                'mKonfirmasiPaud',
            ]);

        return BaseCollection::make($q->paginate((int)$request->get('count', 10)));
    }

    public function petugas(PaudKelas $kelas, Request $request)
    {
        $q = $kelas->paudKelasPetugases()
            ->where([
                'k_petugas_paud'    => $request->get('k_petugas_paud'),
                // 'k_konfirmasi_paud' => MKonfirmasiPaud::BERSEDIA,
            ])
            ->with([
                'akun',
                'akun.mKota',
                'akun.mPropinsi',
                'paudPetugas',
                'mKonfirmasiPaud',
            ]);

        return BaseCollection::make($q->paginate((int)$request->get('count', 10)));
    }

    /**
     * @throws FlowException
     */
    public function updateVerval(PaudKelas $kelas, Request $request)
    {
        $kelas = $this->service->verval(akun(), $kelas, $request->all());
        return BaseResource::make($kelas);
    }

    /**
     * @throws FlowException
     */
    public function batalVerval(PaudKelas $kelas)
    {
        $kelas = $this->service->batalVerval(akun(), $kelas);
        return BaseResource::make($kelas);
    }
}
