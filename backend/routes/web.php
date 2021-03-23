<?php

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

Route::get('/', function (\Illuminate\Http\Request $request) {
    $response = [
        'data' => [
            'domain' => $request->getHost(),
            'time'   => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
        ],
    ];

    if (akun()) {
        $response['data']['akun'] = akun();
    }

    if (ptk()) {
        $response['data']['ptk'] = ptk();
    }

    return response()->json($response);
});

Route::match(['get', 'post'], 'auth/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::match(['get', 'post'], 'auth/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
