<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Instansi Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['cas', 'auth:web', 'forcejson', 'dbtransaction']], function () {
    Route::get('preferensi', [\App\Http\Controllers\Admin\PreferensiController::class, 'index']);
});
