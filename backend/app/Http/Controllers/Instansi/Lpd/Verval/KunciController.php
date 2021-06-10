<?php

namespace App\Http\Controllers\Instansi\Lpd\Verval;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\PaudInstansi;
use App\Services\Instansi\LpdService;

class KunciController extends Controller
{
    public function __construct(protected LpdService $service)
    {
    }

    public function update(PaudInstansi $paudInstansi)
    {
        return BaseResource::make($this->service->kunci(akun(), $paudInstansi));
    }

    public function delete(PaudInstansi $paudInstansi)
    {
        return BaseResource::make($this->service->batalKunci(akun(), $paudInstansi));
    }
}
