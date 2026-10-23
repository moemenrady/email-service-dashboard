<?php

namespace App\Exceptions;

use Exception;

class MxRouteApiException extends Exception
{
    protected ?string $errorCode;

    protected ?string $field;

    protected int $statusCode;

    protected array $responseData;

    public function __construct(
        string $message = 'An error occurred while communicating with the mail server.',
        int $statusCode = 500,
        ?string $errorCode = null,
        ?string $field = null,
        array $responseData = []
    ) {
        // Sanitize any raw domain / host leaks from upstream error message
        $sanitizedMessage = self::sanitizeMessage($message);
        parent::__construct($sanitizedMessage, $statusCode);

        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->field = $field;
        $this->responseData = $responseData;
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getField(): ?string
    {
        return $this->field;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }

    /**
     * Sanitize messages to guarantee zero infrastructure leaks to clients.
     */
    public static function sanitizeMessage(string $rawMessage): string
    {
        // Remove direct mentions of mxroute or internal servers
        $cleaned = preg_replace('/mxroute(\.[a-z]+)?/i', 'Mail System', $rawMessage);
        $cleaned = preg_replace('/DirectAdmin/i', 'Control Panel', $cleaned);
        $cleaned = preg_replace('/fusion\.mxrouting\.net/i', 'Mail Server', $cleaned);
        $cleaned = preg_replace('/eagle\.mxlogin\.com/i', 'Mail Server', $cleaned);

        return $cleaned ?: 'An unexpected mail service error occurred.';
    }
}
