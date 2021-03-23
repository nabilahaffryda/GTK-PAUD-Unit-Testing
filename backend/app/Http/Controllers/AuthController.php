<?php

namespace App\Http\Controllers;

use App\Exceptions\FlowException;
use App\Models\Akun;
use App\Models\Ptk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Subfission\Cas\Facades\Cas;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $instansiId = $request->get('instansi_id');

        Cas::authenticate();
        if (Cas::isAuthenticated()) {
            $pasporId = Cas::getCurrentUser();

            if (Auth::guard('web')->check() && Auth::guard('web')->id() != $pasporId) {
                Auth::guard('web')->logout();
            }

            if (Auth::guard('ptk')->check() && ptk()->paspor_id != $pasporId) {
                Auth::guard('ptk')->logout();
            }

            if ($akun = Akun::wherePasporId($pasporId)->first()) {
               Auth::guard('web')->login($akun);
               app('log-akses')->logAkses();
            }

            if ($ptk = Ptk::wherePasporId($pasporId)->first()) {
                Auth::guard('ptk')->login($ptk);
                app('log-akses')->logAkses();
            }

            if (Auth::guard('web')->check()) {
                if ($instansiId) {
                    return redirect(config('app.ui_url') . '/i/' . $instansiId . '/home');
                }

                return redirect(config('app.ui_url'));
            }

            if (Auth::guard('ptk')->check()) {
                $ptk = ptk();

                // TODO: cek syarat

                return redirect(config('app.ui_url'));
            }
        }

        request()->merge(['format' => 'html']);

        $error = "Untuk masuk silakan menggunakan akun Admin Program Sekolah Penggerak";
        throw new FlowException($error);
    }

    public function logout(Request $request)
    {
        foreach (config('auth.guards') as $guard => $val) {
            if (Auth::guard($guard)->check()) {
                app('log-akses')->logAkses();
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->flush();
        $request->session()->regenerate();

        cas()->logout(config('simpkb.url'));
    }
}
