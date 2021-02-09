<?php

namespace App\Http\Middleware;

use App\Models\Akun;
use App\Models\Ptk;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class Cas extends \Subfission\Cas\Middleware\CASAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        if ($this->cas->isAuthenticated()) {
            $pasporId = $this->cas->getCurrentUser();
            if (Auth::guard('web')->check() && Auth::guard('web')->id() != $pasporId) {
                Auth::guard('web')->logout();
            }

            if (Auth::guard('ptk')->check()) {
                /** @var Ptk $ptk */
                $ptk = Auth::guard('ptk')->user();
                if ($ptk && $ptk->paspor_id != $pasporId) {
                    Auth::guard('web')->logout();
                }
            }

            // Store the user credentials in a Laravel managed session
            session()->put('cas_user', $this->cas->user());

            if ($this->cas->isMasquerading()) {
                if (!Auth::guard('ptk')->check()) {
                    if ($ptk = Ptk::wherePasporId($this->cas->getCurrentUser())->first()) {
                        Auth::guard('ptk')->login($ptk);
                    }
                }

                if (!Auth::guard('web')->check()) {
                    if ($akun = Akun::wherePasporId($this->cas->getCurrentUser())->first()) {
                        Auth::guard('web')->login($akun);
                    }
                }
            }


        } else {
            if ($request->route()->getName() != 'logout') {
                throw new AuthenticationException("Unauthorized.");
            }
        }

        return $next($request);
    }
}
