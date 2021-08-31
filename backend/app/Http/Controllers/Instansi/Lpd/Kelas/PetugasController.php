<?php

namespace App\Http\Controllers\Instansi\Lpd\Kelas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Kelas\CreatePetugasRequest;
use App\Http\Requests\Instansi\Lpd\Kelas\IndexPetugasRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudDiklat;
use App\Models\PaudKelas;
use App\Models\PaudKelasPetugas;
use App\Services\Instansi\KelasService;
use Illuminate\Http\Request;

class PetugasController extends Controller
{
    public function __construct(protected KelasService $service)
    {
    }

    public function index(PaudDiklat $paudDiklat, PaudKelas $kelas, IndexPetugasRequest $request)
    {
        return BaseCollection::make($this->service->indexPetugas($paudDiklat, $kelas, $request->validated())
            ->paginate((int)$request->get('count', 10)));
    }

    public function create(PaudDiklat $paudDiklat, PaudKelas $kelas, CreatePetugasRequest $request)
    {
        $paudPetugases = $this->service->createPetugas($paudDiklat, $kelas, $request->validated());

        return BaseCollection::make($paudPetugases);
    }

    public function delete(PaudDiklat $paudDiklat, PaudKelas $kelas, PaudKelasPetugas $petugas)
    {
        $this->service->validateKelas($paudDiklat, $kelas);
        $this->service->validateKelasBaru($kelas);

        $petugas->delete();

        return true;
    }
}
