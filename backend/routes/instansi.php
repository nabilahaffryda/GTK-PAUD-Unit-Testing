<?php

use App\Http\Controllers\Instansi\AdminController;
use App\Http\Controllers\Instansi\Akun\AdminKelasController;
use App\Http\Controllers\Instansi\Akun\AdminProgramController;
use App\Http\Controllers\Instansi\Akun\AdminProgramLpdController;
use App\Http\Controllers\Instansi\Akun\OperatorLpdController;
use App\Http\Controllers\Instansi\Akun\PembimbingPraktikController;
use App\Http\Controllers\Instansi\Akun\PengajarBimtekController;
use App\Http\Controllers\Instansi\Akun\PengajarController;
use App\Http\Controllers\Instansi\Akun\PengajarTambahanController;
use App\Http\Controllers\Instansi\AkunController;
use App\Http\Controllers\Instansi\IndexController;
use App\Http\Controllers\Instansi\LpdController;
use App\Http\Controllers\Instansi\Pengajar\Profil\BerkasController;
use App\Http\Controllers\Instansi\Pengajar\Profil\PeranController;
use App\Http\Controllers\Instansi\Pengajar\ProfilController;
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

    Route::get('akun/groups', [AkunController::class, 'groups']);
    Route::get('akun/email/{email}', [AkunController::class, 'email']);

    Route::group(['prefix' => 'akun/admin-program'], function () {
        Route::get('', [AdminProgramController::class, 'index']);
        Route::get('download', [AdminProgramController::class, 'download']);
        Route::post('create', [AdminProgramController::class, 'create']);
        Route::get('{paudAdmin}', [AdminProgramController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [AdminProgramController::class, 'update']);
        Route::post('{paudAdmin}/delete', [AdminProgramController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [AdminProgramController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/admin-program-lpd'], function () {
        Route::get('', [AdminProgramLpdController::class, 'index']);
        Route::get('download', [AdminProgramLpdController::class, 'download']);
        Route::post('create', [AdminProgramLpdController::class, 'create']);
        Route::get('{paudAdmin}', [AdminProgramLpdController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [AdminProgramLpdController::class, 'update']);
        Route::post('{paudAdmin}/delete', [AdminProgramLpdController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [AdminProgramLpdController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/operator-lpd'], function () {
        Route::get('', [OperatorLpdController::class, 'index']);
        Route::get('download', [OperatorLpdController::class, 'download']);
        Route::post('create', [OperatorLpdController::class, 'create']);
        Route::get('{paudAdmin}', [OperatorLpdController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [OperatorLpdController::class, 'update']);
        Route::post('{paudAdmin}/delete', [OperatorLpdController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [OperatorLpdController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/pengajar-bimtek'], function () {
        Route::get('', [PengajarBimtekController::class, 'index']);
        Route::get('download', [PengajarBimtekController::class, 'download']);
        Route::post('create', [PengajarBimtekController::class, 'create']);
        Route::get('{paudAdmin}', [PengajarBimtekController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [PengajarBimtekController::class, 'update']);
        Route::post('{paudAdmin}/delete', [PengajarBimtekController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [PengajarBimtekController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/pengajar'], function () {
        Route::get('', [PengajarController::class, 'index']);
        Route::get('download', [PengajarController::class, 'download']);
        Route::post('create', [PengajarController::class, 'create']);
        Route::get('{paudAdmin}', [PengajarController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [PengajarController::class, 'update']);
        Route::post('{paudAdmin}/delete', [PengajarController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [PengajarController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/pengajar-tambahan'], function () {
        Route::get('', [PengajarTambahanController::class, 'index']);
        Route::get('download', [PengajarTambahanController::class, 'download']);
        Route::post('create', [PengajarTambahanController::class, 'create']);
        Route::get('{paudAdmin}', [PengajarTambahanController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [PengajarTambahanController::class, 'update']);
        Route::post('{paudAdmin}/delete', [PengajarTambahanController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [PengajarTambahanController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/pembimbing-praktik'], function () {
        Route::get('', [PembimbingPraktikController::class, 'index']);
        Route::get('download', [PembimbingPraktikController::class, 'download']);
        Route::post('create', [PembimbingPraktikController::class, 'create']);
        Route::get('{paudAdmin}', [PembimbingPraktikController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [PembimbingPraktikController::class, 'update']);
        Route::post('{paudAdmin}/delete', [PembimbingPraktikController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [PembimbingPraktikController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/admin-kelas'], function () {
        Route::get('', [AdminKelasController::class, 'index']);
        Route::get('download', [AdminKelasController::class, 'download']);
        Route::post('create', [AdminKelasController::class, 'create']);
        Route::get('{paudAdmin}', [AdminKelasController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [AdminKelasController::class, 'update']);
        Route::post('{paudAdmin}/delete', [AdminKelasController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [AdminKelasController::class, 'reset']);
    });

    Route::group(['prefix' => 'pengajar/profil'], function () {
        Route::get('', [ProfilController::class, 'index']);
        Route::post('{pengajar}/update', [ProfilController::class, 'update']);

        Route::get('{pengajar}/update-peran', [PeranController::class, 'update']);

        Route::get('{pengajar}/berkas', [BerkasController::class, 'index']);
        Route::post('{pengajar}/berkas/create', [BerkasController::class, 'create']);

        Route::post('berkas/{berkas}/delete', [BerkasController::class, 'delete']);
    });
});
