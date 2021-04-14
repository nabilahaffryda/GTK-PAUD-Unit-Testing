<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Models\MGroup;

class PengajarBimtekController extends AkunController
{
    protected $kGroup = MGroup::PENGAJAR_BIMTEK_DIKLAT_PAUD;
}
