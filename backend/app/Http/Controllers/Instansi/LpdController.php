<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\CreateRequest;
use App\Http\Requests\Instansi\Lpd\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudInstansi;
use App\Services\Instansi\LpdService;
use Illuminate\Http\Request;

class LpdController extends Controller
{
    public function __construct(
        protected LpdService $service,
    )
    {
    }

    /**
     * @param Request $request
     * @return BaseCollection
     */
    public function index(Request $request)
    {
        $params = $request->input('filter', []);

        return BaseCollection::make($this
            ->service
            ->query($params)
            ->paginate((int)$request->get('count', 10))
        );
    }

    /**
     * @param CreateRequest $request
     * @return BaseResource
     */
    public function create(CreateRequest $request)
    {
        $paudInstansi = $this
            ->service
            ->create($request->validated())
            ->load('instansi');

        return BaseResource::make($paudInstansi);
    }

    /**
     * @param PaudInstansi $paudInstansi
     * @return BaseResource
     */
    public function fetch(PaudInstansi $paudInstansi)
    {
        return BaseResource::make($this->service->fetch($paudInstansi));
    }

    /**
     * @return BaseResource
     */
    public function update(PaudInstansi $paudInstansi, UpdateRequest $request)
    {
        $paudInstansi = $this
            ->service
            ->update($paudInstansi, $request->validated());

        return BaseResource::make($paudInstansi);
    }

    public function download(Request $request)
    {
        return $this->service->download($request->all());
    }

}
