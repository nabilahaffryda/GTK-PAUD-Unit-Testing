<?php


namespace App\Remotes;


use GuzzleHttp\Psr7\Request;

class Sertifikat extends Remote
{
    protected $httpMethod = 'GET';
    protected $token = null;

    public function __construct()
    {
        $url = config('services.sertifikat-api.url');
        parent::__construct($url, true);

        $this->token = config('services.sertifikat-api.token');
    }

    protected function encode($method, $args)
    {
        $uri = implode('/', array_filter([$this->object, $method]));

        return new Request($this->httpMethod, $uri, [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $this->token"
        ], json_encode($args));
    }

    public function list($params)
    {
        return $this->request('', $params);
    }

    public function create(array $params)
    {
        $this->httpMethod = 'POST';
        return $this->request('api/create', $params);
    }

    public function preview(array $params)
    {
        $this->httpMethod = 'POST';

        $request = $this->encode('api/preview', $params);

        return $this->client->send($request);
    }

    public function get(string $noSertifikat)
    {
        $this->httpMethod = 'GET';
        return $this->request('api/sertifikat/fetch/' . $noSertifikat, []);
    }

    public function search(array $params)
    {
        $this->httpMethod = 'GET';

        $params['program'] = 'paud';
        $params['access-token']= '56854661d2c836cf260e9a83aec77a389543e2c6cef427d802d4d901898fef';

        // https://sertifikat-api.simpkb.id/backend/api?program=paud&access-token=56854661d2c836cf260e9a83aec77a389543e2c6cef427d802d4d901898fef&nomor=110580101501002010115
        return $this->request('api', $params);
    }
}
