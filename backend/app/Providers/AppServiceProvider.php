<?php

namespace App\Providers;

use App;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Support\ServiceProvider;
use Log;
use Monolog\Handler\StreamHandler;
use Sentry;

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

        if (App::runningInConsole() && config('app.debug')) {
            DB::listen(function ($event) {
                // Format binding data for sql insertion
                $bindings = [];
                foreach ($event->bindings as $binding) {
                    if ($binding instanceof DateTime) {
                        $bindings[] = $binding->format('\'Y-m-d H:i:s\'');
                    } else if (is_numeric($binding)) {
                        $bindings[] = $binding;
                    } else {
                        $bindings[] = "'$binding'";
                    }
                }

                // Insert bindings into query
                $query = str_replace(array('%', '?'), array('%%', '%s'), $event->sql);
                $query = vsprintf($query, $bindings);

                Log::debug('SQL: ' . $query);
            });
        }

        Sentry\configureScope(function (Sentry\State\Scope $scope): void {
            $scope->setTags([
                'app' => App::runningInConsole() ? 'console' : 'backend',
            ]);
        });
    }
}
