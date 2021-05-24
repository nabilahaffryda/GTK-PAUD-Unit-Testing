<?php

namespace App\Http\Controllers\Instansi\Lpd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Diklat\CreateRequest;
use App\Http\Requests\Instansi\Lpd\Diklat\IndexRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudDiklat;
use App\Services\Instansi\DiklatService;
use Illuminate\Http\Request;

class DiklatController extends Controller
{
    public function __construct(protected DiklatService $service)
    {
    }

    public function index(IndexRequest $request)
    {
        return BaseCollection::make($this->service->index(instansi(), $request->validated())
            ->paginate((int)$request->get('count', 10)));
    }

    public function create(CreateRequest $request)
    {
        $paudDiklat = $this
            ->service
            ->create(instansi(), $request->validated())
            ->load('paudPeriode');

        return BaseResource::make($paudDiklat);
    }

    public function fetch(PaudDiklat $paudDiklat)
    {
        return BaseResource::make($this->service->fetch($paudDiklat)
            ->loadMissing(['mVervalPaud', 'paudInstansiBerkases']));
    }

    public function update( PaudDiklat $paudDiklat, CreateRequest $request)
    {
        return BaseResource::make($this->service->update(instansi(), $paudDiklat, $request->validated()));
    }

    public function delete(PaudDiklat $paudDiklat)
    {
        return BaseResource::make($this->service->delete($paudDiklat));
    }
}
