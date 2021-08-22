<?php

namespace App\Providers;

use App\Auth\CasAkunUserProvider;
use App\Auth\CasUserProvider;
use App\Models\Akun;
use App\Models\PaudAkses;
use App\Services\AksesService;
use App\Services\AkunService;
use Auth;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /** @var null|Collection|PaudAkses[] */
    protected $akseses = null;

    /** @var null|PaudAkses[][] */
    protected $akunAkseses = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('cas', function ($app, array $config) {
            return new CasUserProvider($config['model']);
        });

        Auth::provider('cas-akun', function ($app, array $config) {
            return new CasAkunUserProvider(Akun::class);
        });

        Gate::before(function (Authorizable $user, string $ability) {
            if ($this->akseses === null) {
                $this->akseses = PaudAkses::all()->keyBy('akses');
            }

            /** @var PaudAkses $akses */
            $akses = $this->akseses->get($ability);
            if (!$akses) {
                $akses = PaudAkses::create([
                    'akses'    => $ability,
                    'label'    => $ability,
                    'guard'    => config('auth.defaults.guard'),
                    'is_aktif' => 0,
                ]);

                $this->akseses->put($ability, $akses);

            } elseif ($akses->is_aktif) {
                if (!isset($this->akunAkseses[instansiId()])) {
                    $akunInstansi = app(AkunService::class)->akunInstansis(instansi());
                    $groups       = app(AkunService::class)->getGroups($akunInstansi);

                    $this->akunAkseses[instansiId()] = app(AksesService::class)->getAkses($groups)->keyBy('paud_akses_id')->all();
                }

                if (!isset($this->akunAkseses[instansiId()][$akses->paud_akses_id])) {
                    return Response::deny("Akun tidak memiliki akses {$akses->akses}");
                }
            }

            return null;
        });

        Gate::after(function ($user, $ability, $result, $arguments) {
            if ($result === null) {
                $akses = $this->akseses->get($ability);
                return !$akses || !$akses->is_aktif;
            }

            return $result;
        });
    }
}
