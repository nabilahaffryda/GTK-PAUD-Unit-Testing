<?php

use App\Http\Controllers\Gtk\PreferensiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| GTK Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:ptk', 'forcejson', 'dbtransaction']], function () {
    Route::get('preferensi', [PreferensiController::class, 'index']);
});
