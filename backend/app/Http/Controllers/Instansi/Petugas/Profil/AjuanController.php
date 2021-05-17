<?php

namespace App\Http\Controllers\Instansi\Petugas\Profil;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\PaudPetugas;
use App\Services\Instansi\PetugasService;

class AjuanController extends Controller
{
    public function __construct(
        protected PetugasService $service
    )
    {
    }

    /**
     * @throws FlowException
     */
    public function create(PaudPetugas $petugas)
    {
        if ($petugas->akun_id != akunId()) {
            abort(404);
        }

        $this->service->ajuanCreate($petugas);
        return BaseResource::make($petugas);
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function delete(PaudPetugas $petugas)
    {
        if ($petugas->akun_id != akunId()) {
            abort(404);
        }

        $this->service->ajuanDelete($petugas);
        return BaseResource::make($petugas);
    }
}
