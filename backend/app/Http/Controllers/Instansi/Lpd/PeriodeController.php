<?php

namespace App\Http\Controllers\Instansi\Lpd;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseCollection;
use App\Models\PaudPeriode;

class PeriodeController extends Controller
{
    public function index()
    {
        return BaseCollection::make(PaudPeriode::all());
    }
}
