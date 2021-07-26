<?php

use App\Http\Controllers\Instansi\AdminKelas;
use App\Http\Controllers\Instansi\Akun;
use App\Http\Controllers\Instansi\AkunController;
use App\Http\Controllers\Instansi\IndexController;
use App\Http\Controllers\Instansi\Diklat;
use App\Http\Controllers\Instansi\Lpd;
use App\Http\Controllers\Instansi\LpdController;
use App\Http\Controllers\Instansi\Petugas;
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
        Route::get('download', [LpdController::class, 'download']);

        Route::get('profil', [Lpd\ProfilController::class, 'index']);
        Route::post('profil/{paudInstansi}/update', [Lpd\ProfilController::class, 'update']);
        Route::post('profil/{paudInstansi}/set-aktif', [Lpd\ProfilController::class, 'setAktif']);
        Route::get('profil/{paudInstansi}/berkas', [Lpd\Profil\BerkasController::class, 'index']);
        Route::post('profil/{paudInstansi}/berkas/create', [Lpd\Profil\BerkasController::class, 'create']);

        Route::get('{paudInstansi}', [LpdController::class, 'fetch']);
        Route::post('{paudInstansi}/update', [LpdController::class, 'update']);
        Route::post('{paudInstansi}/ajuan/create', [Lpd\Profil\AjuanController::class, 'create']);
        Route::post('{paudInstansi}/ajuan/delete', [Lpd\Profil\AjuanController::class, 'delete']);

        Route::post('berkas/{berkas}/delete', [Lpd\Profil\BerkasController::class, 'delete']);
    });

    Route::group(['prefix' => 'verval'], function () {
        Route::get('lpd', [Lpd\VervalController::class, 'index']);
        Route::get('lpd/download', [Lpd\VervalController::class, 'download']);
        Route::get('lpd/{paudInstansi}', [Lpd\VervalController::class, 'fetch']);
        Route::post('lpd/{paudInstansi}', [Lpd\VervalController::class, 'update']);
        Route::get('lpd/{paudInstansi}/batal', [Lpd\Verval\BatalController::class, 'update']);
        Route::get('lpd/{paudInstansi}/kunci', [Lpd\Verval\KunciController::class, 'update']);
        Route::get('lpd/{paudInstansi}/batal-kunci', [Lpd\Verval\KunciController::class, 'delete']);

        Route::get('petugas', [Petugas\VervalController::class, 'index']);
        Route::get('petugas/{petugas}', [Petugas\VervalController::class, 'fetch']);
        Route::post('petugas/{petugas}', [Petugas\VervalController::class, 'update']);
        Route::get('petugas/{petugas}/batal', [Petugas\Verval\BatalController::class, 'update']);
        Route::get('petugas/{petugas}/kunci', [Petugas\Verval\KunciController::class, 'update']);
        Route::get('petugas/{petugas}/batal-kunci', [Petugas\Verval\KunciController::class, 'delete']);
    });

    Route::group(['prefix' => 'periode'], function () {
        Route::get('', [Diklat\PeriodeController::class, 'index']);
        Route::post('create', [Diklat\PeriodeController::class, 'create']);
        Route::get('{periode}', [Diklat\PeriodeController::class, 'fetch']);
        Route::post('{periode}/update', [Diklat\PeriodeController::class, 'update']);
    });

    Route::group(['prefix' => 'diklat'], function () {
        Route::get('periode', [Lpd\Diklat\PeriodeController::class, 'index']);
        Route::get('mapel-kelas', [Lpd\Diklat\MapelKelasController::class, 'index']);

        Route::get('', [Lpd\DiklatController::class, 'index']);
        Route::post('create', [Lpd\DiklatController::class, 'create']);
        Route::get('{paudDiklat}', [Lpd\DiklatController::class, 'fetch']);
        Route::post('{paudDiklat}/update', [Lpd\DiklatController::class, 'update']);
        Route::post('{paudDiklat}/delete', [Lpd\DiklatController::class, 'delete']);

        Route::group(['prefix' => '{paudDiklat}'], function () {
            Route::get('kelas', [Lpd\KelasController::class, 'index']);
            Route::post('kelas/create', [Lpd\KelasController::class, 'create']);
            Route::get('kelas/{kelas}', [Lpd\KelasController::class, 'fetch']);
            Route::post('kelas/{kelas}/update', [Lpd\KelasController::class, 'update']);

            Route::post('kelas/{kelas}/ajuan/create', [Lpd\Kelas\AjuanController::class, 'create']);
            Route::post('kelas/{kelas}/ajuan/delete', [Lpd\Kelas\AjuanController::class, 'delete']);

            Route::get('kelas/{kelas}/peserta', [Lpd\Kelas\PesertaController::class, 'index']);
            Route::get('kelas/{kelas}/peserta/{peserta}/delete', [Lpd\Kelas\PesertaController::class, 'delete']);

            Route::get('kelas/{kelas}/petugas', [Lpd\Kelas\PetugasController::class, 'index']);
            Route::get('kelas/{kelas}/petugas/kandidat', [Lpd\Kelas\PetugasKandidatController::class, 'index']);
            Route::post('kelas/{kelas}/petugas/create', [Lpd\Kelas\PetugasController::class, 'create']);
            Route::get('kelas/{kelas}/petugas/{petugas}/delete', [Lpd\Kelas\PetugasController::class, 'delete']);
        });
    });

    Route::group(['prefix' => 'akun/admin-program'], function () {
        Route::get('', [Akun\AdminProgramController::class, 'index']);
        Route::get('download', [Akun\AdminProgramController::class, 'download']);
        Route::post('create', [Akun\AdminProgramController::class, 'create']);
        Route::get('{paudAdmin}', [Akun\AdminProgramController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\AdminProgramController::class, 'update']);
        Route::post('{paudAdmin}/aktif', [Akun\AdminProgramController::class, 'aktif']);
        Route::post('{paudAdmin}/non-aktif', [Akun\AdminProgramController::class, 'nonAktif']);
        Route::post('{paudAdmin}/delete', [Akun\AdminProgramController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\AdminProgramController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/admin-gtk'], function () {
        Route::get('', [Akun\AdminGtkController::class, 'index']);
        Route::get('download', [Akun\AdminGtkController::class, 'download']);
        Route::post('create', [Akun\AdminGtkController::class, 'create']);
        Route::get('{paudAdmin}', [Akun\AdminGtkController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\AdminGtkController::class, 'update']);
        Route::post('{paudAdmin}/aktif', [Akun\AdminGtkController::class, 'aktif']);
        Route::post('{paudAdmin}/non-aktif', [Akun\AdminGtkController::class, 'nonAktif']);
        Route::post('{paudAdmin}/delete', [Akun\AdminGtkController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\AdminGtkController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/admin-program-lpd'], function () {
        Route::get('', [Akun\AdminProgramLpdController::class, 'index']);
        Route::get('download', [Akun\AdminProgramLpdController::class, 'download']);
        Route::post('create', [Akun\AdminProgramLpdController::class, 'create']);
        Route::get('{paudAdmin}', [Akun\AdminProgramLpdController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\AdminProgramLpdController::class, 'update']);
        Route::post('{paudAdmin}/aktif', [Akun\AdminProgramLpdController::class, 'aktif']);
        Route::post('{paudAdmin}/non-aktif', [Akun\AdminProgramLpdController::class, 'nonAktif']);
        Route::post('{paudAdmin}/delete', [Akun\AdminProgramLpdController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\AdminProgramLpdController::class, 'reset']);
    });

    Route::group(['prefix' => 'akun/operator-lpd'], function () {
        Route::get('', [Akun\OperatorLpdController::class, 'index']);
        Route::get('download', [Akun\OperatorLpdController::class, 'download']);
        Route::post('create', [Akun\OperatorLpdController::class, 'create']);
        Route::get('{paudAdmin}', [Akun\OperatorLpdController::class, 'fetch']);
        Route::post('{paudAdmin}/update', [Akun\OperatorLpdController::class, 'update']);
        Route::post('{paudAdmin}/aktif', [Akun\OperatorLpdController::class, 'aktif']);
        Route::post('{paudAdmin}/non-aktif', [Akun\OperatorLpdController::class, 'nonAktif']);
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
        Route::post('{paudAdmin}/aktif', [Akun\PengajarBimtekController::class, 'aktif']);
        Route::post('{paudAdmin}/non-aktif', [Akun\PengajarBimtekController::class, 'nonAktif']);
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
        Route::post('{paudAdmin}/aktif', [Akun\PengajarController::class, 'aktif']);
        Route::post('{paudAdmin}/non-aktif', [Akun\PengajarController::class, 'nonAktif']);
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
        Route::post('{paudAdmin}/aktif', [Akun\PengajarTambahanController::class, 'aktif']);
        Route::post('{paudAdmin}/non-aktif', [Akun\PengajarTambahanController::class, 'nonAktif']);
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
        Route::post('{paudAdmin}/aktif', [Akun\PembimbingPraktikController::class, 'aktif']);
        Route::post('{paudAdmin}/non-aktif', [Akun\PembimbingPraktikController::class, 'nonAktif']);
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
        Route::post('{paudAdmin}/aktif', [Akun\AdminKelasController::class, 'aktif']);
        Route::post('{paudAdmin}/non-aktif', [Akun\AdminKelasController::class, 'nonAktif']);
        Route::post('{paudAdmin}/delete', [Akun\AdminKelasController::class, 'delete']);
        Route::post('{paudAdmin}/reset', [Akun\AdminKelasController::class, 'reset']);
    });

    Route::group(['prefix' => 'petugas/profil'], function () {
        Route::get('', [Petugas\ProfilController::class, 'index']);
        Route::post('{petugas}/update', [Petugas\ProfilController::class, 'update']);

        Route::post('{petugas}/ajuan/create', [Petugas\Profil\AjuanController::class, 'create']);
        Route::post('{petugas}/ajuan/delete', [Petugas\Profil\AjuanController::class, 'delete']);

        Route::get('{petugas}/diklat', [Petugas\Profil\DiklatController::class, 'index']);
        Route::post('{petugas}/diklat/update', [Petugas\Profil\DiklatController::class, 'update']);

        Route::get('{petugas}/berkas', [Petugas\Profil\BerkasController::class, 'index']);
        Route::post('{petugas}/berkas/create', [Petugas\Profil\BerkasController::class, 'create']);

        Route::post('berkas/{berkas}/delete', [Petugas\Profil\BerkasController::class, 'delete']);
    });

    Route::group(['prefix' => 'admin-kelas/profil'], function () {
        Route::get('', [AdminKelas\ProfilController::class, 'index']);
        Route::post('{admin}/update', [AdminKelas\ProfilController::class, 'update']);
    });
});
