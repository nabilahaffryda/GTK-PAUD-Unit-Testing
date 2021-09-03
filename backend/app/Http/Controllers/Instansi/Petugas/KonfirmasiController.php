<?php

namespace App\Http\Controllers\Instansi\Petugas;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudKelasPetugas;
use App\Services\Instansi\PetugasKelasService;

class KonfirmasiController extends Controller
{
    public function __construct(
        protected PetugasKelasService $service,
    )
    {
    }

    public function index()
    {
        return BaseCollection::make($this
            ->service
            ->listKonfirmasiKesediaan(akunId())
            ->with([
                'mPetugasPaud',
                'mKonfirmasiPaud',
                'paudKelas.paudDiklat.paudPeriode',
                'paudKelas.paudDiklat.paudInstansi',
            ])
            ->get()
        );
    }

    public function fetch(PaudKelasPetugas $kelasPetugas)
    {
        $kelasPetugas
            ->load([
                'mPetugasPaud',
                'mKonfirmasiPaud',
                'paudKelas.paudDiklat.paudInstansi',
                'paudKelas.paudDiklat.paudPeriode',
                'paudKelas.paudDiklat.mPropinsi',
                'paudKelas.paudDiklat.mKota',
                'paudKelas.mKecamatan',
                'paudKelas.mKelurahan',
            ]);

        return BaseResource::make($kelasPetugas);
    }

    /**
     * @throws FlowException
     */
    public function setuju(PaudKelasPetugas $kelasPetugas)
    {
        return BaseResource::make($this->service->konfirmasiBersedia($kelasPetugas));
    }

    /**
     * @throws FlowException
     */
    public function tidakSetuju(PaudKelasPetugas $kelasPetugas)
    {
        return BaseResource::make($this->service->konfirmasiTidakBersedia($kelasPetugas));
    }

    /**
     * @throws FlowException
     */
    public function reset(PaudKelasPetugas $kelasPetugas)
    {
        return BaseResource::make($this->service->resetKonfirmasi($kelasPetugas));
    }
}
