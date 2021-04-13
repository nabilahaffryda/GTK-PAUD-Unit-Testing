<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Models\MGroup;

class PembimbingPraktikController extends AkunController
{
    protected $kGroup = MGroup::PEMBIMBING_PRAKTIK_DIKLAT_PAUD;
}
