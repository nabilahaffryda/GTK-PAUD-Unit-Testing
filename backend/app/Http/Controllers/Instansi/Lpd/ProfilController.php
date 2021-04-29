<?php

namespace App\Http\Controllers\Instansi\Lpd;

use App\Http\Controllers\Controller;
use App\Http\Requests\FotoRequest;
use App\Http\Requests\Instansi\Lpd\UpdateRequest;
use App\Http\Resources\BaseResource;
use App\Models\PaudInstansi;
use App\Services\Instansi\LpdService;

class ProfilController extends Controller
{
    public function __construct(protected LpdService $service)
    {
    }

    public function index()
    {
        $akun         = akun();
        $operator     = $this->service->getOperatorLpd($akun, instansi());
        $paudInstansi = $this->service->getPaudInstansi(instansi(), $operator);
        $status       = $this->service->getStatusLengkap($paudInstansi);

        if ($paudInstansi->diklat) {
            $paudInstansi->diklat = json_decode($paudInstansi->diklat);
        }

        return BaseResource::make($paudInstansi)
            ->additional([
                'meta' => [
                    'status_lengkap' => $status,
                ],
            ]);
    }

    public function update(PaudInstansi $paudInstansi, UpdateRequest $request, FotoRequest $foto)
    {
        if ($paudInstansi->instansi_id != instansiId()) {
            abort(404);
        }

        $this->service->update($paudInstansi, $request->validated(), $foto->data, $foto->ext);

        return BaseResource::make($paudInstansi->loadMissing('instansi'));
    }
}
