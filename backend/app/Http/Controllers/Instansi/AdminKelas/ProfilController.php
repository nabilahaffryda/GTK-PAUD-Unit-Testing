<?php

namespace App\Http\Controllers\Instansi\AdminKelas;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FotoRequest;
use App\Http\Requests\Instansi\AdminKelas\ProfilUpdateRequest;
use App\Http\Resources\BaseResource;
use App\Models\PaudAdmin;
use App\Services\Instansi\AdminKelasService;

class ProfilController extends Controller
{
    public function __construct(
        protected AdminKelasService $service
    )
    {
    }

    public function index()
    {
        $akun   = akun();
        $admin  = $this->service->getAdmin($akun);
        $status = $this->service->getStatusLengkap($admin);

        return BaseResource::make($admin)
            ->additional([
                'meta' => [
                    'status_lengkap' => $status,
                ],
            ]);
    }

    /**
     * @throws FlowException
     * @throws SaveException
     */
    public function update(PaudAdmin $admin, ProfilUpdateRequest $request, FotoRequest $foto)
    {
        if ($admin->akun_id != akunId()) {
            abort(404);
        }

        $this->service->update($admin, $request->validated(), $foto->data, $foto->ext);

        return BaseResource::make($admin->loadMissing(['akun']));
    }
}
