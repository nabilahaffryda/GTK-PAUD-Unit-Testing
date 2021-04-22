<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Models\MGroup;

class AdminGtkController extends AkunController
{
    protected $kGroup = MGroup::ADM_GTK_PAUD_DIKLAT_PAUD;
}
