<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\ApiResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
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
        $this->renderable(function (Throwable $e) {
            $this->buildResponse($e);
        });
    }

    public function buildResponse(Throwable $e, $request = null)
    {
        if ($e instanceof ValidationException) {
            return $this->formValidationErrorAlert('Validation error occurred', Arr::flatten($e->errors()));
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->notFoundResponse('Model cannot be found');
        }
        if ($e instanceof NotFoundHttpException) {
            return $this->clientError($e->getMessage() || "Resource cannot be found", 404);
        }
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        }
        if ($e instanceof HttpException) {
            return $this->jsonResponse($e->getMessage(), $e->getStatusCode());
        }
        if ($e instanceof HttpClientException) {
            return $this->jsonResponse($e->getMessage(), $e->getStatusCode());
        }
        return $this->serverErrorAlert($e->getMessage());
    }
}
