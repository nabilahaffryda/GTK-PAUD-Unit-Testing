<?php

namespace App\Http\Controllers\Instansi\Diklat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Diklat\Periode\CreateRequest;
use App\Http\Requests\Instansi\Diklat\Periode\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudPeriode;
use App\Services\Instansi\PeriodeService;

class PeriodeController extends Controller
{
    public function __construct(
        protected PeriodeService $service
    )
    {
    }

    public function index()
    {
        return BaseCollection::make($this->service->index()->get());
    }

    public function create(CreateRequest $request)
    {
        $paudPeriodes = $this->service->create($request->input('data'));

        return BaseCollection::make($paudPeriodes);
    }

    public function fetch(PaudPeriode $periode)
    {
        return BaseResource::make($periode);
    }

    public function update(PaudPeriode $periode, UpdateRequest $request)
    {
        $paudPeriode = $this->service->update($periode, $request->validated());

        return BaseResource::make($paudPeriode);
    }

    public function delete(PaudPeriode $periode)
    {
        $paudPeriode = $this->service->delete($periode);

        return BaseResource::make($paudPeriode);
    }
}
