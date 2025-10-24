<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CustomApiException extends Exception
{
    protected $statusCode;
    protected string $errorCode;
    protected ?array $errorDetails;

    public function __construct(
        string $message,
        string $errorCode,
        int $statusCode = Response::HTTP_BAD_REQUEST,
        ?array $errorDetails = null,
        Throwable $previous = null,
        int $code = 0
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
        $this->statusCode = $statusCode;
        $this->errorDetails = $errorDetails;
    }
    public function render(): JsonResponse
    {
        $response = [
            'code' => $this->errorCode,
            'message' => $this->getMessage(),
        ];

        if ($this->errorDetails) {
            $response['errors'] = $this->errorDetails;
        }

        return response()->json($response, $this->statusCode);
    }
}