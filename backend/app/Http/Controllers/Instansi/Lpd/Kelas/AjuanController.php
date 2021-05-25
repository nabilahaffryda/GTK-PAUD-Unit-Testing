<?php

namespace App\Http\Controllers\Instansi\Lpd\Kelas;

use App\Exceptions\FlowException;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResource;
use App\Models\MVervalPaud;
use App\Models\PaudDiklat;
use App\Models\PaudKelas;
use App\Services\Instansi\KelasService;
use Carbon\Carbon;

class AjuanController extends Controller
{
    public function __construct(protected KelasService $service)
    {
    }

    public function create(PaudDiklat $paudDiklat, PaudKelas $kelas)
    {
        $kelas = $this->service->ajuan($paudDiklat, $kelas);

        return BaseResource::make($kelas);
    }

    public function delete(PaudDiklat $paudDiklat, PaudKelas $kelas)
    {
        $kelas = $this->service->batalAjuan($paudDiklat, $kelas);

        return BaseResource::make($kelas);
    }
}
