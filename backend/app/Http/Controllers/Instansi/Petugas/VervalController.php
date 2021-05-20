<?php

namespace App\Http\Controllers\Instansi\Petugas;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Petugas\Verval\IndexRequest;
use App\Http\Requests\Instansi\Petugas\Verval\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudPetugas;
use App\Services\Instansi\PetugasService;

class VervalController extends Controller
{
    public function __construct(protected PetugasService $service)
    {
    }

    public function index(IndexRequest $request)
    {
        return BaseCollection::make($this->service->index($request->validated())
            ->paginate((int)$request->get('count', 10)));
    }

    /**
     * @throws FlowException
     */
    public function update(PaudPetugas $petugas, UpdateRequest $request)
    {
        return BaseResource::make($this->service->ajuanVerval(akun(), $petugas, $request->validated()));
    }

    public function fetch(PaudPetugas $petugas)
    {
        return BaseResource::make($this->service->fetch($petugas));
    }
}
