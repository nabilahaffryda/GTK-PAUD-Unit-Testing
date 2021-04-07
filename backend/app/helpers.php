<?php

use App\Models\Akun;
use App\Models\Instansi;
use App\Models\Ptk;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * @return Authenticatable|Akun|null
 */
function akun()
{
    return Auth::guard('akun')->user();
}

function akunId()
{
    return akun()?->akun_id;
}

/**
 * @return Authenticatable|Ptk|null
 */
function ptk()
{
    return Auth::guard('ptk')->user();
}

/**
 * @return Instansi|null
 */
function instansi()
{
    return app()->bound('INSTANSI') ? resolve('INSTANSI') : null;
}

function instansiId()
{
    return instansi()?->instansi_id;
}
