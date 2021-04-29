<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Http\Requests\Instansi\Admin\CreateRequest;
use App\Http\Requests\Instansi\Admin\UpdateRequest;
use App\Http\Resources\BaseResource;
use App\Models\MGroup;
use App\Models\MVervalPaud;
use App\Models\PaudAdmin;
use App\Services\Instansi\PengajarService;
use Illuminate\Http\Request;

class PengajarTambahanController extends AkunController
{
    protected $kGroup = MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD;

    public function create(CreateRequest $request)
    {
        $this->validateGroup();

        $params = array_merge($request->validated(), [
            'k_group' => $this->kGroup,
        ]);

        $paudAdmin = $this->service->create(instansi(), $params);
        app(PengajarService::class)->create($paudAdmin, [
            'k_verval_paud' => MVervalPaud::KANDIDAT,
            'is_tambahan'   => 1,
            'is_pembimbing' => 0,
        ]);
        return BaseResource::make($paudAdmin);
    }

    public function update(UpdateRequest $request, PaudAdmin $paudAdmin)
    {
        app(PengajarService::class)->validateAdmin($paudAdmin);
        return parent::update($request, $paudAdmin);
    }
}
