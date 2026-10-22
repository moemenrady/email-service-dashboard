<?php

namespace App\Exceptions;

class MxRouteValidationException extends MxRouteApiException
{
    public function __construct(
        string $message = 'Validation failed on the mail server.',
        ?string $field = null,
        int $statusCode = 422,
        ?string $errorCode = 'VALIDATION_ERROR',
        array $responseData = []
    ) {
        parent::__construct(
            message: $message,
            statusCode: $statusCode,
            errorCode: $errorCode,
            field: $field,
            responseData: $responseData
        );
    }
}
