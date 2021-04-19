<?php

namespace App\Http\Controllers\Instansi\Pengajar\Profil;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\PaudPengajar;
use App\Services\Instansi\PengajarService;

class AjuanController extends Controller
{
    public function __construct(
        protected PengajarService $service
    )
    {
    }

    /**
     * @throws FlowException
     */
    public function create(PaudPengajar $pengajar)
    {
        if ($pengajar->akun_id != akunId()) {
            abort(404);
        }

        $this->service->ajuanCreate($pengajar);
        return BaseResource::make($pengajar);
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function delete(PaudPengajar $pengajar)
    {
        if ($pengajar->akun_id != akunId()) {
            abort(404);
        }

        $this->service->ajuanDelete($pengajar);
        return BaseResource::make($pengajar);
    }
}
