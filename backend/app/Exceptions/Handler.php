<?php

namespace App\Exceptions;

use DB;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        FlowException::class,
        SaveException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    protected function renderError(FlowException|SaveException $e)
    {
        return new JsonResponse(
            config('app.debug') ? [
                'message'   => $e->getMessage(),
                'exception' => get_class($e),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => collect($e->getTrace())->map(function ($trace) {
                    return Arr::except($trace, ['args']);
                })->all(),
            ] : [
                'message' => $e->getMessage(),
            ],
            520,
            options: JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (FlowException $e, $request) {
            if (!$request->expectsJson()) {
                return $this->prepareResponse($request, $e);
            }

            return $this->renderError($e);
        });

        $this->renderable(function (SaveException $e, $request) {
            if (!$request->expectsJson()) {
                return $this->prepareResponse($request, $e);
            }

            return $this->renderError($e);
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        DB::rollBack(0);

        if (app()->bound('sentry') && $this->shouldReport($e)) {
            app('sentry')->captureException($e);
        }

        return parent::render($request, $e);
    }
}
