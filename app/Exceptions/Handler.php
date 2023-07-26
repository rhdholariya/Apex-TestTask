<?php

namespace App\Exceptions;

use App\Services\ResponseBuilder;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($this->isApiCall($request)) {

            if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                return ResponseBuilder::error(
                    ["Data not found"],
                    404,
                    null,404
                );
            }

            $data = ['error' => $exception->getMessage()];
            if (config('app.debug')) {
                $data = [
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTrace(),
                    'class' => get_class($exception)
                ];
            }
            if ($exception instanceof AuthenticationException){
                return ResponseBuilder::unauthorized();
            }

            return ResponseBuilder::error(null,
                400,
                $data
            );
        }

        return parent::render($request, $exception);
    }

    /**
     * Determines if request is an api call.
     *
     * If the request URI contains '/api/'.
     *
     * @param Request $request
     * @return bool
     */
    protected function isApiCall(Request $request)
    {
        return strpos($request->getUri(), '/api/') !== false;
    }
}
