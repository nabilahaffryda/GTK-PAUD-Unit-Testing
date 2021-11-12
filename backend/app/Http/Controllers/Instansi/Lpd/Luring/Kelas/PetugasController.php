<?php

namespace App\Http\Controllers\Instansi\Lpd\Luring\Kelas;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Luring\Kelas\CreatePetugasRequest;
use App\Http\Requests\Instansi\Lpd\Luring\Kelas\IndexPetugasRequest;
use App\Http\Resources\BaseCollection;
use App\Models\PaudDiklatLuring;
use App\Models\PaudKelasLuring;
use App\Models\PaudKelasPetugasLuring;
use App\Services\Instansi\KelasLuringService;

class PetugasController extends Controller
{
    public function __construct(
        protected KelasLuringService $service,
    )
    {
    }

    /**
     * @throws FlowException
     */
    public function index(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, IndexPetugasRequest $request)
    {
        return BaseCollection::make($this
            ->service
            ->indexPetugas($diklat, $kelas, $request->validated())
            ->paginate((int)$request->get('count', 10))
        );
    }

    /**
     * @throws FlowException
     */
    public function candidate(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, IndexPetugasRequest $request)
    {
        return BaseCollection::make($this
            ->service
            ->indexPetugasKandidat($diklat, $kelas, $request->validated())
            ->paginate((int)$request->get('count', 10))
        );
    }

    /**
     * @throws FlowException
     */
    public function create(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, CreatePetugasRequest $request)
    {
        $paudPetugases = $this->service->createPetugas($diklat, $kelas, $request->validated());

        return BaseCollection::make($paudPetugases);
    }

    /**
     * @throws FlowException
     */
    public function delete(PaudDiklatLuring $diklat, PaudKelasLuring $kelas, PaudKelasPetugasLuring $petugas)
    {
        $this->service->validateKelas($diklat, $kelas);
        $this->service->validateKelasBaru($kelas);

        $petugas->delete();

        return $petugas;
    }
}
