<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Models\MGroup;

class AdminProgramLpdController extends AkunController
{
    protected $kGroup = MGroup::AP_LPD_DIKLAT_PAUD;
}
