<?php namespace App\Http\Middleware;

use Closure;
use DB;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DBTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null ...$guards
     * @return mixed
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        DB::beginTransaction();
        try {
            $response = $next($request);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        if ($response instanceof Response && $response->getStatusCode() > 399) {
            DB::rollBack();
        } else {
            DB::commit();
        }

        return $response;
    }
}
