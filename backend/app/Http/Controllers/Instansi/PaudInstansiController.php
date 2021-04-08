<?php

namespace App\Http\Controllers\Instansi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\PaudInstansi\CreateRequest;
use App\Http\Resources\BaseResource;
use App\Models\Instansi;
use App\Models\PaudInstansi;
use App\Services\InstansiService;

class PaudInstansiController extends Controller
{
    public function __construct(
        protected InstansiService $service,
    )
    {
    }

    public function create(CreateRequest $request)
    {
        $instansi = new Instansi();

        $instansi->nama       = $request->nama;
        $instansi->email      = $request->email;
        $instansi->alamat     = $request->alamat;
        $instansi->k_propinsi = $request->k_propinsi;
        $instansi->k_kota     = $request->k_kota;

        $paudInstansi = new PaudInstansi();

        $paudInstansi->kodepos               = $request->kodepos;
        $paudInstansi->nama_penanggung_jawab = $request->nama_penanggung_jawab;
        $paudInstansi->telp_penanggung_jawab = $request->telp_penanggung_jawab;

        $this->service->create($instansi, $paudInstansi);

        return BaseResource::make($paudInstansi);
    }
}
