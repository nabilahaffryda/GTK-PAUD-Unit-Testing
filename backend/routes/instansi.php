<?php

use App\Http\Controllers\Instansi\AdminKelas;
use App\Http\Controllers\Instansi\Akun;
use App\Http\Controllers\Instansi\AkunController;
use App\Http\Controllers\Instansi\IndexController;
use App\Http\Controllers\Instansi\Lpd;
use App\Http\Controllers\Instansi\LpdController;
use App\Http\Controllers\Instansi\Pengajar;
use App\Http\Controllers\Instansi\Pembimbing;
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

    Route::get('akun/groups', [AkunController::class, 'groups']);
    Route::get('akun/email/{email}', [AkunController::class, 'email']);

    Route::group(['prefix' => 'lpd'], function () {
        Route::get('', [LpdController::class, 'index']);
        Route::post('create', [LpdController::class, 'create']);

        Route::get('profil', [Lpd\ProfilController::class, 'index']);
        Route::post('profil/{paudInstansi}/update', [Lpd\ProfilController::class, 'update']);
        Route::get('profil/{paudInstansi}/berkas', [Lpd\Profil\BerkasController::class, 'index']);
        Route::post('profil/{paudInstansi}/berkas/create', [Lpd\Profil\BerkasController::class, 'create']);

        Route::get('{paudInstansi}', [LpdController::class, 'fetch']);
        Route::post('{paudInstansi}/ajuan/create', [Lpd\Profil\AjuanController::class, 'create']);
        Route::post('{paudInstansi}/ajuan/delete', [Lpd\Profil\AjuanController::class, 'delete']);
    });

    Route::group(['prefix' => 'verval'], function () {
        Route::get('lpd', [Lpd\VervalController::class, 'index']);
        Route::get('lpd/{paudInstansi}', [Lpd\VervalController::class, 'fetch']);
        Route::post('lpd/{paudInstansi}', [Lpd\VervalController::class, 'update']);

        Route::get('pengajar', [Pengajar\VervalController::class, 'index']);
        Route::get('pengajar/{pengajar}', [Pengajar\VervalController::class, 'fetch']);
        Route::post('pengajar/{pengajar}', [Pengajar\VervalController::class, 'update']);
    });

    Route::group(['prefix' => 'akun/admin-program'], function () {
        Route::get('', [Akun\AdminProgramController::class, 'index']);
        Route::get('download', [Akun\AdminProgramController::class, 'download']);
        Route::post('create', [Akun\AdminProgramController::class, 'create']);
        Route::get('{paudAdmin}', [Akun\AdminProgramController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\AdminProgramController::class, 'update']);
        Route::post('{paudAdmin}/delete', [Akun\AdminProgramController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\AdminProgramController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/admin-program-lpd'], function () {
        Route::get('', [Akun\AdminProgramLpdController::class, 'index']);
        Route::get('download', [Akun\AdminProgramLpdController::class, 'download']);
        Route::post('create', [Akun\AdminProgramLpdController::class, 'create']);
        Route::get('{paudAdmin}', [Akun\AdminProgramLpdController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\AdminProgramLpdController::class, 'update']);
        Route::post('{paudAdmin}/delete', [Akun\AdminProgramLpdController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\AdminProgramLpdController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/operator-lpd'], function () {
        Route::get('', [Akun\OperatorLpdController::class, 'index']);
        Route::get('download', [Akun\OperatorLpdController::class, 'download']);
        Route::post('create', [Akun\OperatorLpdController::class, 'create']);
        Route::get('{paudAdmin}', [Akun\OperatorLpdController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\OperatorLpdController::class, 'update']);
        Route::post('{paudAdmin}/delete', [Akun\OperatorLpdController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\OperatorLpdController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/pengajar-bimtek'], function () {
        Route::get('', [Akun\PengajarBimtekController::class, 'index']);
        Route::get('download', [Akun\PengajarBimtekController::class, 'download']);
        Route::get('download-aktivasi', [Akun\PengajarBimtekController::class, 'downloadAktivasi']);
        Route::get('template', [Akun\PengajarBimtekController::class, 'template']);
        Route::post('create', [Akun\PengajarBimtekController::class, 'create']);
        Route::post('upload', [Akun\PengajarBimtekController::class, 'upload']);
        Route::get('{paudAdmin}', [Akun\PengajarBimtekController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\PengajarBimtekController::class, 'update']);
        Route::post('{paudAdmin}/delete', [Akun\PengajarBimtekController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\PengajarBimtekController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/pengajar'], function () {
        Route::get('', [Akun\PengajarController::class, 'index']);
        Route::get('download', [Akun\PengajarController::class, 'download']);
        Route::get('download-aktivasi', [Akun\PengajarController::class, 'downloadAktivasi']);
        Route::get('template', [Akun\PengajarController::class, 'template']);
        Route::post('create', [Akun\PengajarController::class, 'create']);
        Route::post('upload', [Akun\PengajarController::class, 'upload']);
        Route::get('{paudAdmin}', [Akun\PengajarController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\PengajarController::class, 'update']);
        Route::post('{paudAdmin}/delete', [Akun\PengajarController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\PengajarController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/pengajar-tambahan'], function () {
        Route::get('', [Akun\PengajarTambahanController::class, 'index']);
        Route::get('download', [Akun\PengajarTambahanController::class, 'download']);
        Route::get('download-aktivasi', [Akun\PengajarTambahanController::class, 'downloadAktivasi']);
        Route::get('template', [Akun\PengajarTambahanController::class, 'template']);
        Route::post('create', [Akun\PengajarTambahanController::class, 'create']);
        Route::post('upload', [Akun\PengajarTambahanController::class, 'upload']);
        Route::get('{paudAdmin}', [Akun\PengajarTambahanController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\PengajarTambahanController::class, 'update']);
        Route::post('{paudAdmin}/delete', [Akun\PengajarTambahanController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\PengajarTambahanController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/pembimbing-praktik'], function () {
        Route::get('', [Akun\PembimbingPraktikController::class, 'index']);
        Route::get('download', [Akun\PembimbingPraktikController::class, 'download']);
        Route::get('download-aktivasi', [Akun\PembimbingPraktikController::class, 'downloadAktivasi']);
        Route::get('template', [Akun\PembimbingPraktikController::class, 'template']);
        Route::post('create', [Akun\PembimbingPraktikController::class, 'create']);
        Route::post('upload', [Akun\PembimbingPraktikController::class, 'upload']);
        Route::get('{paudAdmin}', [Akun\PembimbingPraktikController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\PembimbingPraktikController::class, 'update']);
        Route::post('{paudAdmin}/delete', [Akun\PembimbingPraktikController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\PembimbingPraktikController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/admin-kelas'], function () {
        Route::get('', [Akun\AdminKelasController::class, 'index']);
        Route::get('download', [Akun\AdminKelasController::class, 'download']);
        Route::get('download-aktivasi', [Akun\AdminKelasController::class, 'downloadAktivasi']);
        Route::get('template', [Akun\AdminKelasController::class, 'template']);
        Route::post('create', [Akun\AdminKelasController::class, 'create']);
        Route::post('upload', [Akun\AdminKelasController::class, 'upload']);
        Route::get('{paudAdmin}', [Akun\AdminKelasController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\AdminKelasController::class, 'update']);
        Route::post('{paudAdmin}/delete', [Akun\AdminKelasController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\AdminKelasController::class, 'reset']);
    });

    Route::group(['prefix' => 'pengajar/profil'], function () {
        Route::get('', [Pengajar\ProfilController::class, 'index']);
        Route::post('{pengajar}/update', [Pengajar\ProfilController::class, 'update']);

        Route::get('{pengajar}/update-peran', [Pengajar\Profil\PeranController::class, 'update']);

        Route::post('{pengajar}/ajuan/create', [Pengajar\Profil\AjuanController::class, 'create']);
        Route::post('{pengajar}/ajuan/delete', [Pengajar\Profil\AjuanController::class, 'delete']);

        Route::get('{pengajar}/berkas', [Pengajar\Profil\BerkasController::class, 'index']);
        Route::post('{pengajar}/berkas/create', [Pengajar\Profil\BerkasController::class, 'create']);

        Route::post('berkas/{berkas}/delete', [Pengajar\Profil\BerkasController::class, 'delete']);
    });

    Route::group(['prefix' => 'pembimbing/profil'], function () {
        Route::get('', [Pembimbing\ProfilController::class, 'index']);
        Route::post('{pembimbing}/update', [Pembimbing\ProfilController::class, 'update']);

        Route::get('{pembimbing}/berkas', [Pembimbing\Profil\BerkasController::class, 'index']);
        Route::post('{pembimbing}/berkas/create', [Pembimbing\Profil\BerkasController::class, 'create']);

        Route::post('berkas/{berkas}/delete', [Pembimbing\Profil\BerkasController::class, 'delete']);
    });

    Route::group(['prefix' => 'admin-kelas/profil'], function () {
        Route::get('', [AdminKelas\ProfilController::class, 'index']);
        Route::post('{admin}/update', [AdminKelas\ProfilController::class, 'update']);
    });
});
