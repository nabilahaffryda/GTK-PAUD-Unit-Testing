<?php

namespace App\Http\Controllers\Instansi\Lpd\Diklat;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseCollection;
use App\Services\Instansi\PeriodeService;

class PeriodeController extends Controller
{
    public function __construct(
        protected PeriodeService $service
    )
    {

    }

    public function index()
    {
        return BaseCollection::make($this->service->index()->get());
    }
}
