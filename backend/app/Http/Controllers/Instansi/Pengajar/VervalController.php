<?php

namespace App\Http\Controllers\Instansi\Pengajar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Pengajar\Verval\IndexRequest;
use App\Http\Requests\Instansi\Pengajar\Verval\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudPengajar;
use App\Services\Instansi\PengajarService;

class VervalController extends Controller
{
    public function __construct(protected PengajarService $service)
    {
    }

    public function index(IndexRequest $request)
    {
        return BaseCollection::make($this->service->index($request->all())
            ->paginate((int)$request->get('count', 10)));
    }

    public function update(PaudPengajar $pengajar, UpdateRequest $request)
    {
        return BaseResource::make($this->service->vervalUpdate(akun(), $pengajar, $request->all()));
    }
}
