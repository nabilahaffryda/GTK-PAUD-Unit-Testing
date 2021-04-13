<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Models\MGroup;

class PengajarController extends AkunController
{
    protected $kGroup = MGroup::PENGAJAR_DIKLAT_PAUD;
}
