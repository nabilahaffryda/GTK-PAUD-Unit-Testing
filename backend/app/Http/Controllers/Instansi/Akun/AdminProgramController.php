<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Models\MGroup;

class AdminProgramController extends AkunController
{
    protected $kGroup = MGroup::AP_GTK_PAUD_DIKLAT_PAUD;
}
