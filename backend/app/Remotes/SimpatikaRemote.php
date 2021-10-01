<?php

namespace App\Remotes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;

class SimpatikaRemote
{
    private ?Client $client = null;
    private array $handlers = [];

    protected function getAuth()
    {
        return [
            config('services.simpatika.username'),
            config('services.simpatika.password'),
        ];
    }

    protected function getClient(): Client
    {
        if (!$this->client) {
            $handlerStack = HandlerStack::create();

            foreach ($this->handlers as $handler) {
                $handlerStack->push(...$handler);
            }

            if (config('app.debug')) {
                $logger    = app()->make('log');
                $formatter = new MessageFormatter('{method} {uri}' . PHP_EOL . '> {req_body}' . PHP_EOL . '< {res_body}');
                $level     = env('LOG_LEVEL', 'debug');

                $handlerStack->push(Middleware::log($logger, $formatter, $level));
            }

            $this->client = new Client([
                'base_uri' => config('services.simpatika.baseurl'),
                'handler'  => $handlerStack,
            ]);
        }

        return $this->client;
    }

    public function pushHandler(callable $middleware, $name = '')
    {
        $this->handlers[] = [$middleware, $name];
    }

    /**
     * @throws GuzzleException
     */
    public function request(Request $request)
    {
        $client   = $this->getClient();
        $response = $client->send($request, [
            'auth' => $this->getAuth(),
        ]);

        $body = (string)$response->getBody();
        return json_decode($body, true);
    }

    /**
     * @throws GuzzleException
     */
    public function searchGuruRA(?int $kKota, string $keyword = '', int $page = 1, int $count = 10)
    {
        $request = new Request('GET', '/ptk/guru-ra/search?' . http_build_query([
                'k_kota'  => $kKota,
                'keyword' => $keyword,
                'page'    => $page,
                'count'   => $count,
            ])
        );

        return $this->request($request);
    }

    /**
     * @throws GuzzleException
     */
    public function fetchGuruRA(array $ptkIds)
    {
        $request = new Request('GET', '/ptk/guru-ra/fetch?' . http_build_query([
                'ptk_id' => $ptkIds,
            ])
        );

        return $this->request($request);
    }
}
