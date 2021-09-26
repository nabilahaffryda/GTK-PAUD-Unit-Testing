<?php

namespace App\Remotes;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
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
     * @throws Exception|GuzzleException
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
     * @throws Exception|GuzzleException
     */
    public function searchGuruRA(string $keyword = '', int $page = 1, int $count = 10)
    {
        $request = new Request('GET', '/ptk/guru-ra/search?' . http_build_query([
                'keyword' => $keyword,
                'page'    => $page,
                'count'   => $count,
            ])
        );

        return $this->request($request);
    }

    /**
     * @throws Exception|GuzzleException
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
