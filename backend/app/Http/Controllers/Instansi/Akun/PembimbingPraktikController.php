<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Http\Requests\Instansi\Admin\CreateRequest;
use App\Http\Resources\BaseResource;
use App\Models\MGroup;
use App\Services\Instansi\PembimbingService;

class PembimbingPraktikController extends AkunController
{
    protected $kGroup = MGroup::PEMBIMBING_PRAKTIK_DIKLAT_PAUD;

    public function create(CreateRequest $request)
    {
        $this->validateGroup();

        $params = array_merge($request->validated(), [
            'k_group' => $this->kGroup,
        ]);

        $paudAdmin = $this->service->create(instansi(), $params);
        app(PembimbingService::class)->create($paudAdmin, []);
        return BaseResource::make($paudAdmin);
    }
}
