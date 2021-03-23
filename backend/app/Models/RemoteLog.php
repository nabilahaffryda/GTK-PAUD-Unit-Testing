<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Builder;
use Log;
use Psr\Http\Message\RequestInterface;

/**
 * App\Models\RemoteLog
 *
 * @property int $remote_log_id
 * @property null|int $log_id
 * @property null|string $class
 * @property null|string $base_uri
 * @property null|string $req_uri
 * @property null|string $req_method
 * @property null|string $req_header
 * @property null|string $req_body
 * @property null|int $resp_status
 * @property null|string $resp_header
 * @property null|string $resp_body
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @method static Builder|RemoteLog whereRemoteLogId($value)
 * @method static Builder|RemoteLog whereLogId($value)
 * @method static Builder|RemoteLog whereClass($value)
 * @method static Builder|RemoteLog whereBaseUri($value)
 * @method static Builder|RemoteLog whereReqUri($value)
 * @method static Builder|RemoteLog whereReqMethod($value)
 * @method static Builder|RemoteLog whereReqHeader($value)
 * @method static Builder|RemoteLog whereReqBody($value)
 * @method static Builder|RemoteLog whereRespStatus($value)
 * @method static Builder|RemoteLog whereRespHeader($value)
 * @method static Builder|RemoteLog whereRespBody($value)
 * @method static Builder|RemoteLog whereCreatedAt($value)
 * @method static Builder|RemoteLog whereUpdatedAt($value)
 */
class RemoteLog extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'remote_log';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'remote_log_id';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'remote_log_id' => 'int',
        'log_id'        => 'int',
        'class'         => 'string',
        'base_uri'      => 'string',
        'req_uri'       => 'string',
        'req_method'    => 'string',
        'req_header'    => 'string',
        'req_body'      => 'string',
        'resp_status'   => 'int',
        'resp_header'   => 'string',
        'resp_body'     => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'remote_log_id',
        'log_id',
        'class',
        'base_uri',
        'req_uri',
        'req_method',
        'req_header',
        'req_body',
        'resp_status',
        'resp_header',
        'resp_body',
    ];

    protected static $enableLogging = true;

    protected function assignRequest(RequestInterface $request)
    {
        try {
            $headers = json_encode($request->getHeaders());

            $this->req_uri    = substr((string)$request->getUri(), 0, 1024);
            $this->req_method = substr($request->getMethod(), 0, 100);
            $this->req_header = substr($headers, 0, (1 << 16) - 1);
            $this->req_body   = substr((string)$request->getBody(), 0, (1 << 32) - 1);

        } catch (Exception $exception) {
            Log::error("gagal assign request ke remote log.\n{$exception->getMessage()}");
        }
    }

    protected function saveResponse(Response $response)
    {
        $headers = json_encode($response->getHeaders());

        try {
            $this->resp_status = $response->getStatusCode();
            $this->resp_header = substr($headers, 0, (1 << 16) - 1);
            $this->resp_body   = substr((string)$response->getBody(), 0, (1 << 32) - 1);
            $this->save();
        } catch (Exception $e) {
            Log::error("gagal simpan request ke remote log.\n{$e->getMessage()}");
        }
    }

    protected function saveException(Exception $exception)
    {
        try {
            $this->resp_status = -1;
            $this->resp_body   = substr($exception->getMessage(), 0, (1 << 32) - 1);
            $this->save();
        } catch (Exception $e) {
            Log::error("gagal simpan exception ke remote log.\n{$e->getMessage()}");
        }
    }

    static function guzzleMiddleware($class)
    {
        return function (callable $handler) use ($class) {
            return function (RequestInterface $request, array $options) use ($class, $handler) {
                if (static::$enableLogging === false) {
                    return $handler($request, $options);
                }

                $remoteLog = new static(['class' => 'Paud-' . $class]);
                $remoteLog->assignRequest($request);

                try {
                    /** @var $response Promise */
                    $response = $handler($request, $options);
                } catch (Exception $exception) {
                    $remoteLog->saveException($exception);
                    throw $exception;
                }

                $response->then(
                    function (Response $response) use ($remoteLog) {
                        if (static::$enableLogging === true || ($response->getStatusCode() >= 300)) {
                            $remoteLog->saveResponse($response);
                        }
                        return $response;
                    },
                    function (RequestException $exception) use ($remoteLog) {
                        $remoteLog->saveException($exception);
                        return $exception;
                    }
                );

                return $response;
            };
        };
    }

    /**
     * Aktifkan mekanisme logging request dan response
     *
     * @param bool $status
     * @return bool
     */
    public static function enableLogging($status = true)
    {
        $old = static::$enableLogging;

        static::$enableLogging = $status;
        return $old;
    }

    /**
     * Nonaktifkan mekanisme logging request dan response
     *
     * @return bool
     */
    public static function disableLogging()
    {
        return static::enableLogging(false);
    }

    /**
     * Eksekusi $fn dengan mekanisme logging request dan response sesuai status
     *
     * @param bool $status
     * @param callable $fn
     * @return mixed
     */
    public static function withLogging($status, callable $fn)
    {
        $old = static::enableLogging($status);
        try {
            return $fn();
        } finally {
            static::enableLogging($old);
        }
    }
}
