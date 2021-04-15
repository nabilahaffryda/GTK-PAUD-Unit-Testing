<?php

namespace App\Http\Controllers\Instansi\Lpd;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
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

        return BaseResource::make($paudInstansi)
            ->additional([
                'meta' => [
                    'status_lengkap' => $status,
                ]
            ]);
    }
}
