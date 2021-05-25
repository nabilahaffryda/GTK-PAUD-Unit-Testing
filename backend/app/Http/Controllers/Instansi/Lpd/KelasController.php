<?php

namespace App\Http\Controllers\Instansi\Lpd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Kelas\CreateRequest;
use App\Http\Requests\Instansi\Lpd\Kelas\IndexRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudDiklat;
use App\Models\PaudKelas;
use App\Services\Instansi\KelasService;

class KelasController extends Controller
{
    public function __construct(protected KelasService $service)
    {
    }

    public function index(PaudDiklat $paudDiklat, IndexRequest $request)
    {
        return BaseCollection::make($this->service->index($paudDiklat, $request->validated())
            ->paginate((int)$request->get('count', 10)));
    }

    public function create(PaudDiklat $paudDiklat, CreateRequest $request)
    {
        $paudKelas = $this->service->create($paudDiklat, $request->validated());

        return BaseResource::make($paudKelas);
    }

    public function fetch(PaudDiklat $paudDiklat, PaudKelas $kelas)
    {
        return BaseResource::make($this->service->fetch($paudDiklat, $kelas));
    }

    public function update(PaudDiklat $paudDiklat, PaudKelas $kelas, CreateRequest $request)
    {
        $paudKelas = $this->service->update($paudDiklat, $kelas, $request->validated());

        return BaseResource::make($paudKelas);
    }
}
