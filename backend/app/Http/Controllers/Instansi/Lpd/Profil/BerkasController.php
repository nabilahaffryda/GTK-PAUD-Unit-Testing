<?php

namespace App\Http\Controllers\Instansi\Lpd\Profil;

use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Lpd\Profil\BerkasCreateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudInstansi;
use App\Models\PaudInstansiBerkas;
use App\Services\Instansi\LpdService;
use Exception;

class BerkasController extends Controller
{
    public function __construct(protected LpdService $service)
    {
    }

    public function index(PaudInstansi $paudInstansi)
    {
        $berkases = $paudInstansi
            ->paudInstansiBerkases
            ->loadMissing(['mBerkasLpdPaud']);

        return BaseCollection::make($berkases);
    }

    public function create(PaudInstansi $paudInstansi, BerkasCreateRequest $request)
    {
        return BaseResource::make($this->service->upload($paudInstansi, $request->k_berkas, $request->file));
    }

    /**
     * @throws Exception
     */
    public function delete(PaudInstansiBerkas $berkas)
    {
        if ($berkas->paud_instansi_id != instansiId()) {
            abort(404);
        }

        $this->service->berkasDelete($berkas);
        return BaseResource::make($berkas);
    }
}
