<?php

namespace App\Http\Controllers\Instansi\Lpd\Luring\Kelas;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\PaudDiklatLuring;
use App\Models\PaudKelasLuring;
use App\Services\Instansi\KelasLuringService;

class AjuanController extends Controller
{
    public function __construct(
        protected KelasLuringService $service,
    )
    {
    }

    /**
     * @throws FlowException
     */
    public function create(PaudDiklatLuring $diklat, PaudKelasLuring $kelas)
    {
        $kelas = $this->service->ajuan($diklat, $kelas);

        return BaseResource::make($kelas);
    }

    /**
     * @throws FlowException
     */
    public function delete(PaudDiklatLuring $diklat, PaudKelasLuring $kelas)
    {
        $kelas = $this->service->batalAjuan($diklat, $kelas);

        return BaseResource::make($kelas);
    }
}
