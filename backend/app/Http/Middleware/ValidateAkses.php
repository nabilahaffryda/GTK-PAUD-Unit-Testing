<?php

namespace App\Http\Middleware;

use App\Models\PaudAkses;
use App\Services\AksesService;
use App\Services\AkunService;
use Closure;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ValidateAkses
{
    public function __construct(
        protected AksesService $service,
        protected AkunService $akunService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string[] ...$guards
     *
     * @return mixed
     *
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $instansi = instansi();
        if (!$instansi) {
            throw new AuthorizationException("Akun tidak memiliki Instansi yg diakses");
        }

        $key   = $this->service->fromRequest($request, 'akun');
        $akses = PaudAkses::whereAkses($key)->where('is_aktif', '1')->first();
        if (!$akses) {
            return $next($request);
        }

        $akunInstansi = $this->akunService->akunInstansis($instansi);
        $groups       = $this->akunService->getGroups($akunInstansi);

        if (!$this->service->isAkses($groups, $akses->paud_akses_id)) {
            throw new AuthorizationException("Akun tidak memiliki akses {$akses->akses}");
        }

        foreach ($request->route()->parameters() as $model) {
            // pastikan Model dan HasPolicies
            if (!($model instanceof Model) || !method_exists($model, 'getPolicyName')) {
                continue;
            }

            if ($ability = $model->getPolicyName($akses->akses)) {
                $inspect = Gate::inspect($ability, $model);
                if ($inspect->denied()) {
                    throw new AuthorizationException($inspect->message());
                }
            }
        }

        return $next($request);
    }
}
