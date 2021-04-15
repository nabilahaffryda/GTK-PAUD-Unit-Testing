<?php

namespace App\Http\Controllers\Instansi\Pengajar\Profil;

use App\Exceptions\FlowException;
use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Pengajar\Profil\BerkasCreateRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\BaseResource;
use App\Models\PaudPengajar;
use App\Models\PaudPengajarBerkas;
use App\Services\Instansi\PengajarService;

class BerkasController extends Controller
{
    public function __construct(
        protected PengajarService $service
    )
    {
    }

    public function index(PaudPengajar $pengajar)
    {
        if ($pengajar->akun_id != akunId()) {
            abort(404);
        }

        $berkases = $pengajar
            ->paudPengajarBerkases
            ->loadMissing(['mBerkasPengajarPaud']);

        return BaseCollection::make($berkases);
    }

    /**
     * @throws SaveException
     * @throws FlowException
     */
    public function create(PaudPengajar $pengajar, BerkasCreateRequest $request)
    {
        if ($pengajar->akun_id != akunId()) {
            abort(404);
        }

        $berkas = $this->service->berkasCreate($pengajar, $request->k_berkas, $request->file);
        return BaseResource::make($berkas);
    }

    public function delete(PaudPengajarBerkas $berkas)
    {
        if ($berkas->paudPengajar->akun_id != akunId()) {
            abort(404);
        }

        $this->service->berkasDelete($berkas);
        return BaseResource::make($berkas);
    }
}
