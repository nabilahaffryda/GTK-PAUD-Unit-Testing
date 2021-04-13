<?php

use App\Http\Controllers\Instansi\AdminController;
use App\Http\Controllers\Instansi\IndexController;
use App\Http\Controllers\Instansi\LpdController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Instansi Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth:akun', 'forcejson', 'valid.instansi', 'valid.akses', 'dbtransaction']], function () {
    Route::get('preferensi', [IndexController::class, 'preferensi']);
    Route::get('master', [IndexController::class, 'master']);
    Route::get('instansi', [IndexController::class, 'instansi']);

    Route::get('lpd', [LpdController::class, 'index']);
    Route::post('lpd/create', [LpdController::class, 'create']);
    Route::get('lpd/{paudInstansi}', [LpdController::class, 'fetch']);

    Route::get('admin', [AdminController::class, 'index']);
    Route::get('admin/download', [AdminController::class, 'download']);
    Route::get('admin/groups', [AdminController::class, 'groups']);
    Route::get('admin/email/{email}', [AdminController::class, 'email']);
    Route::post('admin/create', [AdminController::class, 'create']);

    Route::get('admin/{paudAdmin}', [AdminController::class, 'fetch']);
    Route::post('admin/{paudAdmin}/update', [AdminController::class, 'update']);
    Route::post('admin/{paudAdmin}/delete', [AdminController::class, 'delete']);
    Route::post('admin/{paudAdmin}/reset', [AdminController::class, 'reset']);
});
