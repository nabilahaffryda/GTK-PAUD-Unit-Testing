<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\CreateRequest;
use App\Http\Resources\BaseResource;
use App\Services\InstansiService;

class LpdController extends Controller
{
    public function __construct(
        protected InstansiService $service,
    )
    {
    }

    public function create(CreateRequest $request)
    {
        $paudInstansi = $this
            ->service
            ->createLpd($request->validated())
            ->load('instansi');

        return BaseResource::make($paudInstansi);
    }
}
