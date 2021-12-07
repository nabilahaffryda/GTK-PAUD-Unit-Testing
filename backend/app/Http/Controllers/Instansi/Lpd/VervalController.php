<?php

namespace App\Http\Controllers\Instansi\Lpd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Verval\IndexRequest;
use App\Http\Requests\Instansi\Lpd\Verval\UpdateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudInstansi;
use App\Services\Instansi\LpdService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class VervalController extends Controller
{
    public function __construct(protected LpdService $service)
    {
    }

    public function index(IndexRequest $request)
    {
        return BaseCollection::make($this->service->indexAjuan($request->validated())
            ->paginate((int)$request->get('count', 10)));
    }

    public function update(PaudInstansi $paudInstansi, UpdateRequest $request)
    {
        return BaseResource::make($this->service->vervalUpdate(akun(), $paudInstansi, $request->validated()));
    }

    public function fetch(PaudInstansi $paudInstansi)
    {
        return BaseResource::make($this->service->fetch($paudInstansi)
            ->loadMissing([
                'mVervalPaud',
                'paudInstansiBerkases' => function ($query) {
                    $query->whereIn('k_berkas_lpd_paud', array_merge(LpdService::BERKAS_VERVAL, LpdService::BERKAS_SERTIFIKAT));
                },
            ]));
    }

    /**
     * @param Request $request
     * @return LengthAwarePaginator|null
     */
    public function download(Request $request)
    {
        return $this->service->downloadVerval($request->all());
    }
}
