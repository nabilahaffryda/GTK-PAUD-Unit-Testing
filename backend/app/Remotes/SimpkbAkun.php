<?php

namespace App\Remotes;

use GuzzleHttp\Psr7\Request;

class SimpkbAkun extends Remote
{
    protected $httpMethod = 'GET';

    public function __construct()
    {
        $url = config('services.simpkb-akun.url');
        parent::__construct($url, true);
    }

    protected function encode($method, $args)
    {
        $uri = implode('/', array_filter([$this->object, $method]));

        if ($this->httpMethod == 'GET') {
            return new Request($this->httpMethod, "{$uri}?" . http_build_query($args));
        }

        return new Request($this->httpMethod, $uri, [
            'Content-Type'  => 'application/json',
        ], json_encode($args));
    }

    public function layanans($passporId)
    {
        $params = [
            'query'     => "{\n  layanan{\n    nama,\n    url, \n    peran, \n    __typename\n  }\n}",
            'paspor_id' => $passporId,
            'access_token' => config('services.simpkb-akun.token'),
        ];

        return $this->request('graphql', $params);
    }
}
