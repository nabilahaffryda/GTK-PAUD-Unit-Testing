<?php

namespace App\Http\Controllers\Instansi\Pembimbing;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FotoRequest;
use App\Http\Requests\Instansi\Pembimbing\ProfilUpdateRequest;
use App\Http\Resources\BaseResource;
use App\Models\PaudPembimbing;
use App\Services\Instansi\PembimbingService;

class ProfilController extends Controller
{
    public function __construct(
        protected PembimbingService $service
    )
    {
    }

    public function index()
    {
        $akun       = akun();
        $pembimbing = $this->service->getPembimbing($akun);
        $pembimbing = $this->service->fetch($pembimbing);
        $status     = $this->service->getStatusLengkap($pembimbing);

        return BaseResource::make($pembimbing)
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
    public function update(PaudPembimbing $pembimbing, ProfilUpdateRequest $request, FotoRequest $foto)
    {
        if ($pembimbing->akun_id != akunId()) {
            abort(404);
        }

        $this->service->update($pembimbing, $request->validated(), $foto->data, $foto->ext);

        return BaseResource::make($pembimbing->loadMissing(['akun']));
    }
}
