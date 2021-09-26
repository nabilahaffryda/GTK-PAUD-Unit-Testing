<?php

namespace App\Remotes;

use GuzzleHttp\Psr7\Request;


abstract class Paspor extends Remote
{
    protected $dataKey = 'data';
    protected $errorKey = 'error.message';
    protected $httpMethod = 'GET';

    /**
     * Paspor constructor.
     */
    public function __construct()
    {
        $url = \Config::get('services.paspor.url');
        parent::__construct($url, true);
    }

    /**
     * @param string $method
     * @param array $args
     */
    protected function encode($method, $args)
    {
        if (!$this->object && $this->httpMethod == 'GET') {
            if ($args) {
                $method .= '?' . http_build_query($args);
            }

            return new Request($this->httpMethod, $method, [], null);
        }

        if ($this->httpMethod == 'POST') {
            return new Request($this->httpMethod, $method, [
                'Content-type' => 'application/json',
            ], json_encode($args));
        }

        if ($this->httpMethod == 'DELETE') {
            return new Request($this->httpMethod, $method, [], null);
        }

        $request = new Request($this->httpMethod, $this->object . '.json?' . http_build_query([
                'service' => $method,
                'data'    => json_encode($args),
            ])
        );

        return $request;
    }

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     * @return array
     * @throws \Exception
     */
    protected function decode($response)
    {
        if (!$response || $response->getStatusCode() != 200) {
            throw new \Exception('Response engine tidak valid');
        }

        parent::decode($response);
    }
}
