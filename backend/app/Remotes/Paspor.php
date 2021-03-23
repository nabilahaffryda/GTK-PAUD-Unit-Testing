<?php

namespace App\Remotes;

use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Container\BindingResolutionException;


abstract class Paspor extends Remote
{
    protected $dataKey = 'data';
    protected $errorKey = 'error.message';

    /**
     * Paspor constructor.
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $url = \Config::get('services.paspor.url');
        parent::__construct($url);
    }

    /**
     * @param string $method
     * @param array $args
     * @return Request
     */
    protected function encode($method, $args)
    {
        return new Request('GET', $this->object . '.json?' . http_build_query([
                'service' => $method,
                'data'    => json_encode($args),
            ]));
    }

    /**
     * @param Response $response
     * @throws Exception
     */
    protected function decode($response)
    {
        if (!$response || $response->getStatusCode() != 200) {
            throw new Exception('Response engine tidak valid');
        }

        parent::decode($response);
    }
}
