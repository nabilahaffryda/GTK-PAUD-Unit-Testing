<?php

namespace App\Http\Controllers\Instansi\Petugas\Profil;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instansi\Petugas\Profil\BerkasCreateRequest;
use App\Http\Resources\BaseCollection;
use App\Models\PaudPetugas;
use App\Services\Instansi\PetugasService;

class DiklatController extends Controller
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

        $diklats = $petugas
            ->paudPetugasDiklats;

        return BaseCollection::make($diklats);
    }

    /**
     * @throws FlowException
     */
    public function update(PaudPetugas $petugas, BerkasCreateRequest $request)
    {
        if ($petugas->akun_id != akunId()) {
            abort(404);
        }

        $this->service->diklatUpdate($petugas, $request->validated());

        $diklats = $petugas
            ->paudPetugasDiklats()
            ->get();

        return BaseCollection::make($diklats);
    }
}
