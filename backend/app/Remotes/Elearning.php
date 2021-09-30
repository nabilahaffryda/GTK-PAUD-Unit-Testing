<?php

namespace App\Remotes;

use App\Models\RemoteLog;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Http;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Client\PendingRequest;

class Elearning
{
    public function getClient(): PendingRequest
    {
        $http = Http::baseUrl(config('services.elearning-api.base-url'))
            ->withOptions([
                'verify' => false,
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

    public function diklatKelasCreate(int $diklatId, array $data): ?array
    {
        return $this
            ->getClient()
            ->post("diklat/{$diklatId}/kelas", $data)
            ->json();
    }

    public function kelasFetch(int $kelasId): ?array
    {
        return $this
            ->getClient()
            ->get("kelas/{$kelasId}")
            ->json();
    }

    public function kelasEnroll(int $kelasId, array $data): ?array
    {
        return $this
            ->getClient()
            ->post("kelas/{$kelasId}", $data)
            ->json();
    }

    public function kelasUnenroll(int $kelasId, array $data): ?array
    {
        return $this
            ->getClient()
            ->post("kelas/{$kelasId}", $data)
            ->json();
    }

    public function userSync(array $data): ?array
    {
        return $this
            ->getClient()
            ->post("user/sync", $data)
            ->json();
    }

    public function userUnsync(array $data): ?array
    {
        return $this
            ->getClient()
            ->post("user/unsync", $data)
            ->json();
    }
}
