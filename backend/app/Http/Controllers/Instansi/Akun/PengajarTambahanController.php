<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Models\MGroup;

class PengajarTambahanController extends AkunController
{
    protected $kGroup = MGroup::PENGAJAR_TAMBAHAN_DIKLAT_PAUD;
}
