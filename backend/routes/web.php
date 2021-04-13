<?php

use App\Http\Controllers\AksesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('home');

Route::match(['get', 'post'], 'auth/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::match(['get', 'post'], 'auth/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth:akun', 'dbtransaction'])->group(function () {
    Route::get('akses', [AksesController::class, 'index']);
    Route::get('akses/groups', [AksesController::class, 'groups']);
    Route::post('akses/save', [AksesController::class, 'save']);
    Route::post('akses/save-aktif', [AksesController::class, 'saveAktif']);
});
