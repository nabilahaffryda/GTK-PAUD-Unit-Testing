<?php

namespace App\Http\Controllers\Instansi\Petugas\Verval;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\PaudPetugas;
use App\Services\Instansi\PetugasService;

class KunciController extends Controller
{
    public function __construct(protected PetugasService $service)
    {
    }

    public function update(PaudPetugas $petugas)
    {
        return BaseResource::make($this->service->kunci(akun(), $petugas));
    }

    public function delete(PaudPetugas $petugas)
    {
        return BaseResource::make($this->service->batalKunci(akun(), $petugas));
    }
}
