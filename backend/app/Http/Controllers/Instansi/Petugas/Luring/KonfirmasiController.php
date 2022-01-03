<?php

namespace App\Http\Controllers\Instansi\Petugas\Luring;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudKelasPetugasLuring;
use App\Services\Instansi\PetugasKelasLuringService;

class KonfirmasiController extends Controller
{
    public function __construct(
        protected PetugasKelasLuringService $service,
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
                'paudKelasLuring.paudDiklatLuring.paudInstansi.instansi',
            ])
            ->get()
        );
    }

    public function fetch(PaudKelasPetugasLuring $kelasPetugas)
    {
        $kelasPetugas
            ->load([
                'mPetugasPaud',
                'mKonfirmasiPaud',
                'paudKelasLuring.paudDiklatLuring.paudInstansi.instansi',
                'paudKelasLuring.paudDiklatLuring.mPropinsi',
                'paudKelasLuring.paudDiklatLuring.mKota',
                'paudKelasLuring.mKecamatan',
                'paudKelasLuring.mKelurahan',
            ]);

        return BaseResource::make($kelasPetugas);
    }

    /**
     * @throws FlowException
     */
    public function setuju(PaudKelasPetugasLuring $kelasPetugas)
    {
        return BaseResource::make($this->service->konfirmasiBersedia($kelasPetugas));
    }

    /**
     * @throws FlowException
     */
    public function tidakSetuju(PaudKelasPetugasLuring $kelasPetugas)
    {
        return BaseResource::make($this->service->konfirmasiTidakBersedia($kelasPetugas));
    }

    /**
     * @throws FlowException
     */
    public function reset(PaudKelasPetugasLuring $kelasPetugas)
    {
        return BaseResource::make($this->service->resetKonfirmasi($kelasPetugas));
    }
}
