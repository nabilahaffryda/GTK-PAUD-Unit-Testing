<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Subfission\Cas\Facades\Cas;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function login(Request $request)
    {
        Cas::authenticate();
        $pasporId = Cas::user();

        if ($akun = Auth::guard('akun')->loginUsingId($pasporId)) {
            app('log-akses')->logAkses();
        }

        if ($ptk = Auth::guard('ptk')->loginUsingId($pasporId)) {
            app('log-akses')->logAkses();
        }

        if (!$akun && !$ptk) {
            $error = "Untuk masuk silakan menggunakan akun yang telah terdaftar pada Program Diklat GTK PAUD";
            abort(Response::HTTP_FORBIDDEN, $error);
        }

        if ($akun) {
            $instansiId = $request->get('instansi_id');
            if ($instansiId) {
                return redirect(config('app.ui_url') . '/i/' . $instansiId . '/home');
            }

            return redirect(config('app.ui_url'));
        }

        // TODO: cek syarat GTK
        return redirect(config('app.ui_url'));
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
