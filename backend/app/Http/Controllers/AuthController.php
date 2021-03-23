<?php

namespace App\Http\Controllers;

use App\Exceptions\FlowException;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Subfission\Cas\Facades\Cas;

class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws FlowException
     */
    public function login(Request $request)
    {
        Cas::authenticate();
        $pasporId = Cas::user();

        if ($akun = Auth::guard('web')->loginUsingId($pasporId)) {
            app('log-akses')->logAkses();
        }

        if ($ptk = Auth::guard('ptk')->loginUsingId($pasporId)) {
            app('log-akses')->logAkses();
        }

        if (!$akun && !$ptk) {
            $error = "Untuk masuk silakan menggunakan akun Program Diklat GTK PAUD";
            throw new FlowException($error);
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
