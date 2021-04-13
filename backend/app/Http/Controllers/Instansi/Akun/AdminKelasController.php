<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Models\MGroup;

class AdminKelasController extends AkunController
{
    protected $kGroup = MGroup::ADM_KELAS_DIKLAT_PAUD;
}
