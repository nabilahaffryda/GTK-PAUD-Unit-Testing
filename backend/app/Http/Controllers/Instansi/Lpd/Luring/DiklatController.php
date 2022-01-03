<?php

namespace App\Http\Controllers\Instansi\Lpd\Luring;

use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Luring\Diklat\CreateRequest;
use App\Http\Requests\Instansi\Lpd\Luring\Diklat\IndexRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudDiklatLuring;
use App\Models\PaudInstansi;
use App\Services\Instansi\DiklatLuringService;
use App\Services\InstansiService;

class DiklatController extends Controller
{
    protected ?PaudInstansi $paudInstansi = null;

    public function __construct(protected DiklatLuringService $service)
    {
    }

    public function getPaudInstansi()
    {
        if (!$this->paudInstansi) {
            $this->paudInstansi = app(InstansiService::class)->getPaudInstansi(instansi());
        }

        return $this->paudInstansi;
    }

    public function index(IndexRequest $request)
    {
        return BaseCollection::make(
            $this->service
                ->index($this->getPaudInstansi(), $request->validated())
                ->paginate((int)$request->get('count', 10))
        );
    }

    /**
     * @throws SaveException
     */
    public function create(CreateRequest $request)
    {
        $paudDiklat = $this
            ->service
            ->create($this->getPaudInstansi(), $request->validated());

        return BaseResource::make($paudDiklat);
    }

    public function fetch(PaudDiklatLuring $diklat)
    {
        return BaseResource::make($this->service->fetch($diklat));
    }

    /**
     * @throws SaveException
     */
    public function update(PaudDiklatLuring $diklat, CreateRequest $request)
    {
        return BaseResource::make($this->service->update($diklat, $request->validated()));
    }

    /**
     * @throws SaveException
     */
    public function delete(PaudDiklatLuring $diklat)
    {
        return BaseResource::make($this->service->delete($diklat));
    }
}
