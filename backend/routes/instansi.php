<?php

use App\Http\Controllers\Instansi\PreferensiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Instansi Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:akun', 'forcejson', 'valid.instansi', 'valid.akses', 'dbtransaction']], function () {
    Route::get('preferensi', [PreferensiController::class, 'index']);
});
