<?php

namespace App\Providers;

use App;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Log;
use Monolog\Handler\StreamHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        setlocale(LC_TIME,
            'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID',
            'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND',
            'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1',
            'Indonesian', 'Indonesia', 'id', 'ID'
        );
        Carbon::setLocale(config('app.locale'));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (App::runningInConsole()) {
            /** @noinspection PhpUndefinedMethodInspection */
            $logger = Log::getLogger();
            $logger->pushHandler(new StreamHandler("php://stdout"));
        }
    }
}
