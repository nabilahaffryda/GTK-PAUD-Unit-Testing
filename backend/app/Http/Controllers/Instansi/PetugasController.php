<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Petugas\IndexRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\MPetugasPaud;
use App\Models\PaudPetugas;
use App\Services\Instansi\PetugasService;

class PetugasController extends Controller
{
    public function __construct(
        protected PetugasService $service
    )
    {
    }

    public function index(IndexRequest $request)
    {
        return BaseCollection::make($this
            ->service
            ->listPetugas($request->validated())
            ->paginate((int)$request->get('count', 10))
        );
    }

    public function fetch(PaudPetugas $petugas)
    {
        if (!in_array($petugas->k_petugas_paud, [MPetugasPaud::PENGAJAR, MPetugasPaud::PEMBIMBING_PRAKTIK])) {
            abort(404);
        }

        $pengajar = $this->service->fetch($petugas);
        return BaseResource::make($pengajar);
    }
}
