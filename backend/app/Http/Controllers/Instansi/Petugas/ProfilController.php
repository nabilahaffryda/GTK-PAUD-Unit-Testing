<?php

namespace App\Http\Controllers\Instansi\Petugas;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FotoRequest;
use App\Http\Requests\Instansi\Petugas\ProfilUpdateRequest;
use App\Http\Resources\BaseResource;
use App\Models\PaudPetugas;
use App\Services\Instansi\PetugasService;

class ProfilController extends Controller
{
    public function __construct(
        protected PetugasService $service
    )
    {
    }

    public function index()
    {
        $akun     = akun();
        $pengajar = $this->service->getPetugas($akun);
        $pengajar = $this->service->fetch($pengajar);
        $status   = $this->service->getStatusLengkap($pengajar);

        return BaseResource::make($pengajar)
            ->additional([
                'meta' => [
                    'status_lengkap' => $status,
                ]
            ]);
    }

    /**
     * @throws FlowException
     * @throws SaveException
     */
    public function update(PaudPetugas $petugas, ProfilUpdateRequest $request, FotoRequest $foto)
    {
        if ($petugas->akun_id != akunId()) {
            abort(404);
        }

        $this->service->update($petugas, $request->validated(), $foto->data, $foto->ext);

        return BaseResource::make($petugas->loadMissing(['akun']));
    }
}
