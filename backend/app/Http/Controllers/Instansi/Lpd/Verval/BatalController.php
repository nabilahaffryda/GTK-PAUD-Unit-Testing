<?php

namespace App\Http\Controllers\Instansi\Lpd\Verval;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\PaudInstansi;
use App\Services\Instansi\LpdService;

class BatalController extends Controller
{
    public function update(PaudInstansi $paudInstansi, LpdService $service)
    {
        return BaseResource::make($service->batalVerval(akun(), $paudInstansi));
    }
}
