<?php

namespace App\Http\Controllers\Instansi\Pembimbing\Profil;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Pembimbing\Profil\BerkasCreateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudPembimbing;
use App\Models\PaudPembimbingBerkas;
use App\Services\Instansi\PembimbingService;

class BerkasController extends Controller
{
    public function __construct(
        protected PembimbingService $service
    )
    {
    }

    public function index(PaudPembimbing $pembimbing)
    {
        if ($pembimbing->akun_id != akunId()) {
            abort(404);
        }

        $berkases = $pembimbing
            ->paudPembimbingBerkases
            ->loadMissing(['mBerkasPembimbingPaud']);

        return BaseCollection::make($berkases);
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function create(PaudPembimbing $pembimbing, BerkasCreateRequest $request)
    {
        if ($pembimbing->akun_id != akunId()) {
            abort(404);
        }

        $berkas = $this->service->berkasCreate($pembimbing, $request->k_berkas, $request->file);
        return BaseResource::make($berkas);
    }

    public function delete(PaudPembimbingBerkas $berkas)
    {
        if ($berkas->paudPembimbing->akun_id != akunId()) {
            abort(404);
        }

        $this->service->berkasDelete($berkas);
        return BaseResource::make($berkas);
    }
}
