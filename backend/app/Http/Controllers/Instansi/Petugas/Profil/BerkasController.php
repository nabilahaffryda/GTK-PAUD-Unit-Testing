<?php

namespace App\Http\Controllers\Instansi\Petugas\Profil;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Petugas\Profil\BerkasCreateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudPetugas;
use App\Models\PaudPetugasBerkas;
use App\Services\Instansi\PetugasService;
use Exception;

class BerkasController extends Controller
{
    public function __construct(
        protected PetugasService $service
    )
    {
    }

    public function index(PaudPetugas $petugas)
    {
        if ($petugas->akun_id != akunId()) {
            abort(404);
        }

        $berkases = $petugas
            ->paudPetugasBerkases
            ->loadMissing(['mBerkasPetugasPaud']);

        return BaseCollection::make($berkases);
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function create(PaudPetugas $petugas, BerkasCreateRequest $request)
    {
        if ($petugas->akun_id != akunId()) {
            abort(404);
        }

        $berkas = $this->service->berkasCreate($petugas, $request->k_berkas, $request->file);
        return BaseResource::make($berkas);
    }

    /**
     * @throws Exception
     */
    public function delete(PaudPetugasBerkas $berkas)
    {
        if ($berkas->paudPetugas->akun_id != akunId()) {
            abort(404);
        }

        $this->service->berkasDelete($berkas);
        return BaseResource::make($berkas);
    }
}
