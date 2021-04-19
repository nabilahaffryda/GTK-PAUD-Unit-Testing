<?php

namespace App\Http\Controllers\Instansi\Lpd\Profil;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\PaudInstansi;
use App\Services\Instansi\LpdService;

class AjuanController extends Controller
{
    public function __construct(protected LpdService $service)
    {
    }

    public function create(PaudInstansi $paudInstansi)
    {
        return BaseResource::make($this->service->ajuanCreate($paudInstansi));
    }
}
