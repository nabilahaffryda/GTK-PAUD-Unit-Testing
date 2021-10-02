<?php

namespace App\Remotes;

use App\Models\RemoteLog;
use App\Remotes\ElearningRemote\DiklatKelasCreateParam;
use App\Remotes\ElearningRemote\KelasEnrollParam;
use App\Remotes\ElearningRemote\KelasUnenrollParam;
use App\Remotes\ElearningRemote\UserSyncParam;
use App\Remotes\ElearningRemote\UserUnsyncParam;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Http;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Client\PendingRequest;

class ElearningRemote
{
    public function getClient(): PendingRequest
    {
        $http = Http::baseUrl(config('services.elearning-api.base-url'))
            ->withOptions([
                'verify' => false,
            ])
            ->withHeaders([
                'Authorization' => 'Bearer ' . config('services.elearning-api.token'),
            ]);

        if (config('services.elearning-api.log-request')) {
            $http->withMiddleware(RemoteLog::guzzleMiddleware(static::class));
        }

        if (config('app.debug')) {
            try {
                $logger    = app()->make('log');
                $formatter = new MessageFormatter('{method} {uri}' . PHP_EOL . '> {req_body}' . PHP_EOL . '< {res_body}');
                $level     = env('LOG_LEVEL', 'debug');

                $http->withMiddleware(Middleware::log($logger, $formatter, $level));
            } catch (BindingResolutionException) {
            }
        }

        return $http;
    }

    public function instansiList(): ?array
    {
        return $this
            ->getClient()
            ->get('instansi')
            ->json();
    }

    public function instansiDiklatList(int $instansiId): ?array
    {
        return $this
            ->getClient()
            ->get("instansi/{$instansiId}/diklat")
            ->json();
    }

    public function diklatFetch(int $diklatId): ?array
    {
        return $this
            ->getClient()
            ->get("diklat/{$diklatId}")
            ->json();
    }

    public function diklatKelasList(int $diklatId): ?array
    {
        return $this
            ->getClient()
            ->get("diklat/{$diklatId}/kelas")
            ->json();
    }

    public function diklatKelasCreate(int $diklatId, DiklatKelasCreateParam $data): ?array
    {
        return $this
            ->getClient()
            ->post("diklat/{$diklatId}/kelas", $data->toArray())
            ->json();
    }

    public function kelasFetch(int $kelasId): ?array
    {
        return $this
            ->getClient()
            ->get("kelas/{$kelasId}")
            ->json();
    }

    public function kelasEnroll(int $kelasId, KelasEnrollParam $data): ?array
    {
        return $this
            ->getClient()
            ->post("kelas/{$kelasId}/enroll", $data->toArray())
            ->json();
    }

    public function kelasUnenroll(int $kelasId, KelasUnenrollParam $data): ?array
    {
        return $this
            ->getClient()
            ->post("kelas/{$kelasId}/unenroll", $data->toArray())
            ->json();
    }

    public function userSync(UserSyncParam $data): ?array
    {
        return $this
            ->getClient()
            ->post("user/sync", $data->toArray())
            ->json();
    }

    public function userUnsync(UserUnsyncParam $data): ?array
    {
        return $this
            ->getClient()
            ->post("user/unsync", $data->toArray())
            ->json();
    }
}
