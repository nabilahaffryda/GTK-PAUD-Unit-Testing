<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\CreateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudAdmin;
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
        return BaseCollection::make($this
            ->service
            ->query($request->all())
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
}
