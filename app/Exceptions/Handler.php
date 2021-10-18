<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e){
            return $this->buildResponse($e);
        });
    }

    public function buildResponse(Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation error occurred',
                'errors' => Arr::flatten($e->errors())
            ], 400);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json(['message' => 'Model cannot be found'], 404);
        }
        if ($e instanceof NotFoundHttpException) {
            return response()->json(['message' => $e->getMessage() || "Resource cannot be found"],
                404);
        }
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        }
        if ($e instanceof HttpException) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        }
        if ($e instanceof HttpClientException) {
            return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
        }
        return response()->json([$e->getMessage()], 500);
    }
}
