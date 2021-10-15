<?php

namespace Tests;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Fluent;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ApiResponse
{
    /**
     * Set the server error response.
     *
     * @param $message
     * @param Exception|null $exception
     *
     * @return JsonResponse
     */
    public function serverErrorAlert($message, Exception $exception = null): JsonResponse
    {
        logger($exception);
        if (null !== $exception) {
            Log::error("{$exception->getMessage()} on line {$exception->getLine()} in {$exception->getFile()}");
        }

        return $this->jsonResponse($message, 500);
    }

    /**
     * Set the server error response.
     *
     * @param $message
     * @param HttpException|null $exception
     * @return JsonResponse
     */
    public function httpErrorAlert($message, HttpException $exception = null): JsonResponse
    {
        if (null !== $exception) {
            Log::error("{$exception->getMessage()} on line {$exception->getLine()} in {$exception->getFile()}");
        }

        return $this->jsonResponse($message, 422);
    }

    /**
     * Set the form validation error response.
     *
     * @param $message
     * @param $data
     *
     * @return JsonResponse
     */
    public function formValidationErrorAlert($message, $data = null): JsonResponse
    {
        return $this->jsonResponse($message, 422, $data);
    }

    /**
     * Set bad request error response.
     *
     * @param $message
     * @param null $data
     *
     * @return JsonResponse
     */
    public function badRequestAlert($message, $data = null): JsonResponse
    {
        return $this->jsonResponse($message, 400, $data);
    }

    /**
     * @param string $message
     * @param array|null $error
     * @return JsonResponse
     */
    public function notFoundResponse(string $message, ?array $error = null): JsonResponse
    {
        return $this->clientError($message, 404, $error);
    }

    /**
     * @param string $message
     * @param int $status
     * @param null $data
     * @return JsonResponse
     */
    public function clientError(string $message, int $status = 400, $data = null): JsonResponse
    {
        return $this->jsonResponse($message, $status, $data);
    }

    /**
     * Set the success response alert.
     *
     * @param $message
     * @param $data
     *
     * @return JsonResponse
     */
    public function successResponse($message, $data = null): JsonResponse
    {
        return $this->jsonResponse($message, 200, $data);
    }

    /**
     * Set the created resource response alert.
     *
     * @param $message
     * @param $data
     *
     * @return JsonResponse
     */
    public function createdResponse($message, $data = null): JsonResponse
    {
        return $this->jsonResponse($message, 201, $data);
    }

    /**
     * Set forbidden request error response.
     *
     * @param $message
     * @param $data
     *
     * @return JsonResponse
     */
    public function forbiddenRequestAlert($message, $data = null): JsonResponse
    {
        return $this->jsonResponse($message, 403, $data);
    }

    /**
     * Return a generic HTTP response
     *
     * @param string $message
     * @param int $status
     * @param null $data
     *
     * @return JsonResponse
     */
    public function jsonResponse(string $message, int $status, $data = null): JsonResponse
    {
        $is_successful = $this->isStatusCodeSuccessful($status);

        $response_data = [
            'status' => $status,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response_data[$is_successful ? 'data' : 'error'] = $data;
        }

        return Response::json($response_data, $status);
    }

    /**
     * Determine if a HTTP status code indicates success
     *
     * @param int $status
     *
     * @return bool
     */
    private function isStatusCodeSuccessful(int $status): bool
    {
        return $status >= 200 && $status < 300;
    }

    /**
     * @param null $data
     * @param string|null $message
     * @return Fluent
     */
    public function ok($data = null, string $message = null)
    {
        return new Fluent([
            "status" => true,
            "data" => $data,
            "message" => $message
        ]);
    }

    /**
     * @param string|null $message
     * @param null $data
     * @return Fluent
     */
    public function bad(string $message = null, $data = null)
    {
        logger($data);
        return new Fluent([
            "status" => false,
            "data" => $data,
            "message" => $message
        ]);
    }
}
