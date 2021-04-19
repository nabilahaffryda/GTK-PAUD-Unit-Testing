<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Http\Requests\Instansi\Admin\CreateRequest;
use App\Http\Resources\BaseResource;
use App\Models\MGroup;
use App\Models\MVervalPaud;
use App\Services\Instansi\PengajarService;

class PengajarController extends AkunController
{
    protected $kGroup = MGroup::PENGAJAR_DIKLAT_PAUD;

    public function create(CreateRequest $request)
    {
        $this->validateGroup();

        $params = array_merge($request->validated(), [
            'k_group' => $this->kGroup,
        ]);

        $paudAdmin = $this->service->create(instansi(), $params);
        app(PengajarService::class)->create($paudAdmin, [
            'k_verval_paud' => MVervalPaud::DISETUJUI,
            'is_tambahan'   => 0,
        ]);
        return BaseResource::make($paudAdmin);
    }
}
