<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class ValidateInstansi
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        $instansiId = $request->route('INSTANSI_ID');

        if ($instansiId) {
            $akunInstansi = akun()
                ?->akunInstansis()
                ->where('is_aktif', 1)
                ->where('instansi_id', $instansiId)
                ->first();

            if ($akunInstansi) {
                $request->merge([
                    'INSTANSI_ID' => $instansiId,
                    'INSTANSI'    => $akunInstansi->instansi,
                ]);

                app()->instance('INSTANSI', $akunInstansi->instansi);

                $request->route()->forgetParameter('INSTANSI_ID');
                return $next($request);
            }
        }

        throw new AuthorizationException("Parameter INSTANSI_ID Tidak ditemukan");
    }
}
