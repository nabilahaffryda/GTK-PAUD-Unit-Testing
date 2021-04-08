<?php

use App\Http\Controllers\Instansi\IndexController;
use App\Http\Controllers\Instansi\PaudInstansiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Instansi Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:akun', 'forcejson', 'valid.instansi', 'valid.akses', 'dbtransaction']], function () {
    Route::get('preferensi', [IndexController::class, 'preferensi']);
    Route::get('master', [IndexController::class, 'master']);

    Route::post('paud-instansi/create', [PaudInstansiController::class, 'create']);
});
