<?php

namespace App\Remotes;

use App\Models\RemoteLog;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\TransferStats;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;

/**
 * Created by PhpStorm.
 * User: Azhar
 * Date: 2/8/2016
 * Time: 9:44 PM
 */
abstract class Remote
{
    protected $object = null;

    protected $client = null;
    protected $verify = true;

    protected $data = null;
    protected $dataKey = null;
    protected $errorKey = null;
    protected $messageKey = null;

    /**
     * Moodle constructor.
     *
     * @param string $url
     * @param bool $logRequest
     * @throws BindingResolutionException
     */
    public function __construct($url, $logRequest = false)
    {
        if ($url) {
            $stack = HandlerStack::create();

            if ($logRequest) {
                $stack->push(RemoteLog::guzzleMiddleware(static::class), static::class);
            }

            if (config('app.debug')) {
                $stack->push(
                    Middleware::log(
                        app()->make('log'),
                        new MessageFormatter('{method} {uri}' . PHP_EOL . '> {req_body}' . PHP_EOL . '< {res_body}'),
                        LogLevel::DEBUG
                    )
                );
            }

            $this->client = new Client([
                'base_uri' => $url,
                'handler'  => $stack,
                'verify'   => $this->verify ? storage_path('cacert.pem') : false,
                'on_stats' => function (TransferStats $stats) {
                    $time = $stats->getTransferTime();
                    if ($time > 0.2) {
                        Log::info("$time - {$stats->getEffectiveUri()}");
                    }
                },
            ]);
        }
    }

    /**
     * @param string $method
     * @param array $args
     * @return Request
     */
    protected function encode($method, $args)
    {
        return new Request('POST', $this->object . '/' . $method, [], http_build_query($args));
    }

    /**
     * @param $response ResponseInterface
     * @throws Exception
     */
    protected function decode($response)
    {
        if (!$response)
            throw new Exception('Response engine tidak valid');

        $body       = (string)$response->getBody();
        $this->data = json_decode($body, true);

        if ($body != 'null' && $this->data === null)
            throw new Exception('Response remote service tidak sesuai spesifikasi');

        $key = $this->errorKey ?: 'error';
        if (Arr::has($this->data, $key)) {
            $error = Arr::get($this->data, $this->messageKey ?: $key);
            if ($error && is_string($error))
                throw new Exception($error);
            else
                throw new Exception('Response remote service error');
        }
    }

    public function getData()
    {
        $key = $this->dataKey;
        if ($key === null || !key_exists($key, (array)$this->data))
            return $this->data;

        return $this->data[$key];
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     */
    public function request($method, $params)
    {
        if ($this->client === null)
            return false;

        $request = $this->encode($method, $params);

        $response = $this->client->send($request);
        $this->decode($response);

        return $this->getData();
    }
}
