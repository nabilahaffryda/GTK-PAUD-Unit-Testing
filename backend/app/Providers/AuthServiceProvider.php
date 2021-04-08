<?php

namespace App\Providers;

use App\Auth\CasAkunUserProvider;
use App\Auth\CasUserProvider;
use App\Models\Akun;
use Auth;
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
    }
}
