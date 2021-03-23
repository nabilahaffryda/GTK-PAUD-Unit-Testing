<?php

namespace App\Http\Middleware;

use App\Jobs\SynchronizeAkunJob;
use App\Models\Akun;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceJson
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!$request->has('format') || $request->get('format') == 'json') {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
