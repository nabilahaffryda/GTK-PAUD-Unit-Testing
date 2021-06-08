<?php

namespace App\Http\Controllers\Instansi\Petugas\Verval;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\PaudPetugas;
use App\Services\Instansi\PetugasService;

class BatalController extends Controller
{
    public function update(PaudPetugas $petugas, PetugasService $service)
    {
        return BaseResource::make($service->batalVerval(akun(), $petugas));
    }
}
