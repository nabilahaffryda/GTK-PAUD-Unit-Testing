<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Instansi\AkunController;
use App\Http\Requests\Instansi\Admin\CreateRequest;
use App\Http\Resources\BaseResource;
use App\Models\MGroup;
use App\Models\MPetugasPaud;
use App\Models\PaudAdmin;
use App\Services\Instansi\PetugasService;
use GuzzleHttp\Exception\GuzzleException;

class AdminKelasController extends AkunController
{
    protected $kGroup = MGroup::ADM_KELAS_DIKLAT_PAUD;

    /**
     * @throws SaveException
     * @throws FlowException
     * @throws GuzzleException
     */
    public function create(CreateRequest $request)
    {
        $this->validateGroup();

        $params = array_merge($request->validated(), [
            'k_group' => $this->kGroup,
        ]);

        $paudAdmin = $this->service->create(instansi(), $params);
        app(PetugasService::class)->create($paudAdmin, [
            'k_petugas_paud' => MPetugasPaud::ADMIN_KELAS,
        ]);
        return BaseResource::make($paudAdmin);
    }

    public function delete(PaudAdmin $paudAdmin)
    {
        app(PetugasService::class)->delete($paudAdmin);
        parent::delete($paudAdmin);
    }
}
