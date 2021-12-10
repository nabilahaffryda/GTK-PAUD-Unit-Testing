<?php

namespace App\Http\Controllers\Instansi\Lpd\Luring\Diklat;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseCollection;
use App\Models\PaudMapelKelas;

class MapelKelasController extends Controller
{
    public function index()
    {
        return BaseCollection::make(PaudMapelKelas::all());
    }
}
