<?php

namespace App\Http\Controllers\Instansi\Lpd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Verval\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudInstansi;
use App\Services\Instansi\LpdService;
use Illuminate\Http\Request;

class VervalController extends Controller
{
    public function __construct(protected LpdService $service)
    {
    }

    public function index(Request $request)
    {
        return BaseCollection::make($this->service->indexAjuan($request->all())
            ->paginate((int)$request->get('count', 10)));
    }

    public function update(PaudInstansi $paudInstansi, UpdateRequest $request)
    {
        return BaseResource::make($this->service->vervalUpdate(akun(), $paudInstansi, $request->all()));
    }
}
