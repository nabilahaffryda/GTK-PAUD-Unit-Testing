<?php

namespace App\Http\Controllers\Instansi\Pengajar\Profil;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FotoRequest;
use App\Http\Requests\Instansi\Pengajar\Profil\PeranUpdateRequest;
use App\Http\Resources\BaseResource;
use App\Models\PaudPengajar;
use App\Services\Instansi\PengajarService;

class PeranController extends Controller
{
    public function __construct(
        protected PengajarService $service
    )
    {
    }

    /**
     * @throws FlowException
     * @throws SaveException
     */
    public function update(PaudPengajar $pengajar, PeranUpdateRequest $request, FotoRequest $foto)
    {
        if ($pengajar->akun_id != akunId()) {
            abort(404);
        }

        $this->service->update($pengajar, $request->validated(), $foto->data, $foto->ext);

        return BaseResource::make($pengajar->loadMissing(['akun']));
    }
}
