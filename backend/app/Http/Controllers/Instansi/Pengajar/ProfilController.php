<?php

namespace App\Http\Controllers\Instansi\Pengajar;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FotoRequest;
use App\Http\Requests\Instansi\Pengajar\ProfilUpdateRequest;
use App\Http\Resources\BaseResource;
use App\Models\PaudPengajar;
use App\Services\Instansi\PengajarService;

class ProfilController extends Controller
{
    public function __construct(
        protected PengajarService $service
    )
    {
    }

    public function index()
    {
        $akun     = akun();
        $pengajar = $this->service->getPengajar($akun);
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
    public function update(PaudPengajar $pengajar, ProfilUpdateRequest $request, FotoRequest $foto)
    {
        if ($pengajar->akun_id != akunId()) {
            abort(404);
        }

        $this->service->update($pengajar, $request->validated(), $foto->data, $foto->ext);

        return BaseResource::make($pengajar->loadMissing(['akun']));
    }
}
