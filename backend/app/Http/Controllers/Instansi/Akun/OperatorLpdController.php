<?php

namespace App\Http\Controllers\Instansi\Akun;

use App\Http\Controllers\Instansi\AkunController;
use App\Models\MGroup;

class OperatorLpdController extends AkunController
{
    protected $kGroup = MGroup::OP_LPD_DIKLAT_PAUD;
}
