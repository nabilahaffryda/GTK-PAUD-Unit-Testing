<?php

namespace App\Http\Controllers\Instansi\Lpd\Kelas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Kelas\IndexPetugasRequest;
use App\Http\Resources\BaseCollection;
use App\Models\PaudDiklat;
use App\Models\PaudKelas;
use App\Services\Instansi\KelasService;
use Illuminate\Http\Request;

class PetugasKandidatController extends Controller
{
    public function __construct(protected KelasService $service)
    {
    }

    public function index(PaudDiklat $paudDiklat, PaudKelas $kelas, IndexPetugasRequest $request)
    {
        return BaseCollection::make($this->service
            ->indexPetugasKandidat($paudDiklat, $kelas, $request->validated())
            ->paginate((int)$request->get('count', 10)));
    }
}
