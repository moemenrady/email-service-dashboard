<?php

namespace App\Exceptions;

class MxRouteRateLimitException extends MxRouteApiException
{
    protected int $retryAfter;

    public function __construct(
        int $retryAfter = 60,
        string $message = '',
        ?string $errorCode = 'RATE_LIMITED',
        array $responseData = []
    ) {
        $this->retryAfter = max(1, $retryAfter);

        $sanitized = !empty($message) ? self::sanitizeMessage($message) : '';

        $fullMessage = "Rate limit reached. " . ($sanitized ? $sanitized . " " : "") . "Please wait {$this->retryAfter} seconds before retrying.";

        parent::__construct(
            message: $fullMessage,
            statusCode: 429,
            errorCode: $errorCode,
            field: null,
            responseData: $responseData
        );
    }

    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}
