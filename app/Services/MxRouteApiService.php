<?php

namespace App\Services;

use App\Exceptions\MxRouteApiException;
use App\Exceptions\MxRouteRateLimitException;
use App\Exceptions\MxRouteValidationException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MxRouteApiService
{
    protected string $baseUrl;

    protected string $server;

    protected string $username;

    protected string $apiKey;

    protected int $timeout;

    protected int $retryAttempts;

    protected int $retryBackoffMs;

    public function __construct(
        ?string $server = null,
        ?string $username = null,
        ?string $apiKey = null,
        ?string $baseUrl = null
    ) {
        $this->server = $server ?? (string) config('mxroute.server');
        $this->username = $username ?? (string) config('mxroute.username');
        $this->apiKey = $apiKey ?? (string) config('mxroute.api_key');
        $this->baseUrl = rtrim($baseUrl ?? (string) config('mxroute.base_url', 'https://api.mxroute.com'), '/');
        $this->timeout = (int) config('mxroute.timeout', 15);
        $this->retryAttempts = (int) config('mxroute.retry_attempts', 2);
        $this->retryBackoffMs = (int) config('mxroute.retry_backoff_ms', 500);
    }

    /**
     * Build the authenticated HTTP client instance.
     */
    protected function client(): PendingRequest
    {
        return Http::baseUrl($this->baseUrl)
            ->timeout($this->timeout)
            ->withHeaders([
                'X-Server' => $this->server,
                'X-Username' => $this->username,
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json',
                'User-Agent' => 'Moemen-Workspace-Proxy/1.0',
            ]);
    }

    /**
     * Send HTTP request with automatic rate-limit detection, backoff, and error mapping.
     *
     * @param  string  $method  GET, POST, PATCH, DELETE
     *
     * @throws MxRouteApiException
     */
    public function request(string $method, string $endpoint, array $data = []): array
    {
        $method = strtoupper($method);
        $endpoint = '/'.ltrim($endpoint, '/');
        $attempt = 0;

        while (true) {
            $attempt++;

            try {
                $client = $this->client();

                $response = match ($method) {
                    'GET' => $client->get($endpoint, $data),
                    'POST' => $client->post($endpoint, $data),
                    'PATCH' => $client->patch($endpoint, $data),
                    'DELETE' => $client->delete($endpoint, $data),
                    default => throw new MxRouteApiException("Unsupported HTTP method [{$method}].", 500),
                };

                // Track rate limit headers in cache for health monitoring
                $this->recordRateLimitHeaders($response);

                // Handle 429 Rate Limiting
                if ($response->status() === 429) {
                    $retryAfter = (int) ($response->header('Retry-After') ?: 60);

                    // If GET and retry attempts remaining and short backoff
                    if ($method === 'GET' && $attempt <= $this->retryAttempts && $retryAfter <= 3) {
                        Log::warning("MXroute API rate limited (429). Retrying in {$retryAfter}s (attempt {$attempt})");
                        sleep($retryAfter);

                        continue;
                    }

                    $body = $response->json() ?? [];
                    $errorMsg = $body['error']['message'] ?? "Mail API rate limit exceeded. Please retry after {$retryAfter} seconds.";
                    throw new MxRouteRateLimitException(
                        retryAfter: $retryAfter,
                        message: MxRouteApiException::sanitizeMessage($errorMsg),
                        errorCode: 'RATE_LIMITED',
                        responseData: $body
                    );
                }

                // If successful (200, 201, 204)
                if ($response->successful()) {
                    if ($response->status() === 204) {
                        return ['success' => true];
                    }

                    return $response->json() ?? ['success' => true];
                }

                // Parse error response
                $this->handleErrorResponse($response);

            } catch (MxRouteApiException $e) {
                throw $e;
            } catch (\Exception $e) {
                Log::error('MXroute API Client Exception: '.$e->getMessage(), [
                    'method' => $method,
                    'endpoint' => $endpoint,
                ]);

                throw new MxRouteApiException(
                    message: 'Failed to communicate with mail server. '.MxRouteApiException::sanitizeMessage($e->getMessage()),
                    statusCode: 500,
                    errorCode: 'SERVER_ERROR'
                );
            }
        }
    }

    /**
     * Map error response to typed exceptions.
     */
    protected function handleErrorResponse(Response $response): void
    {
        $status = $response->status();
        $body = $response->json() ?? [];
        $error = $body['error'] ?? [];
        $code = $error['code'] ?? 'SERVER_ERROR';
        $message = $error['message'] ?? 'An error occurred while contacting the mail server.';
        $field = $error['field'] ?? null;

        // Rate limit check in body
        if ($code === 'RATE_LIMITED' || $status === 429) {
            $retryAfter = (int) ($response->header('Retry-After') ?: 60);
            throw new MxRouteRateLimitException($retryAfter, $message, $code, $body);
        }

        // Validation error
        if ($code === 'VALIDATION_ERROR' || $status === 422 || ($status === 400 && $field)) {
            throw new MxRouteValidationException(
                message: $message,
                field: $field,
                statusCode: $status,
                errorCode: $code,
                responseData: $body
            );
        }

        // Conflict (e.g. Account already exists)
        if ($code === 'CONFLICT' || $status === 409) {
            $sanitized = MxRouteApiException::sanitizeMessage($message);
            if (empty($sanitized) || $sanitized === 'Conflict') {
                $sanitized = 'The specified email account or alias already exists.';
            }
            throw new MxRouteApiException(
                message: $sanitized,
                statusCode: 409,
                errorCode: 'CONFLICT',
                field: $field,
                responseData: $body
            );
        }

        // Not Found
        if ($code === 'NOT_FOUND' || $status === 404) {
            throw new MxRouteApiException(
                message: 'The requested resource (domain, mailbox, or forwarder) was not found.',
                statusCode: 404,
                errorCode: 'NOT_FOUND',
                field: $field,
                responseData: $body
            );
        }

        // Unauthorized
        if ($code === 'UNAUTHORIZED' || $status === 401) {
            throw new MxRouteApiException(
                message: 'Authentication to the mail system failed. Please contact your system administrator.',
                statusCode: 401,
                errorCode: 'UNAUTHORIZED',
                field: $field,
                responseData: $body
            );
        }

        // Forbidden
        if ($code === 'FORBIDDEN' || $status === 403) {
            throw new MxRouteApiException(
                message: 'You do not have permission to perform this mail server action.',
                statusCode: 403,
                errorCode: 'FORBIDDEN',
                field: $field,
                responseData: $body
            );
        }

        // Business Logic Error
        if ($code === 'BUSINESS_ERROR') {
            throw new MxRouteApiException(
                message: MxRouteApiException::sanitizeMessage($message),
                statusCode: 400,
                errorCode: 'BUSINESS_ERROR',
                field: $field,
                responseData: $body
            );
        }

        // Fallback for general status codes
        throw new MxRouteApiException(
            message: MxRouteApiException::sanitizeMessage($message),
            statusCode: $status,
            errorCode: $code,
            field: $field,
            responseData: $body
        );
    }

    /**
     * Store rate limit tracking data into cache for status monitoring.
     */
    protected function recordRateLimitHeaders(Response $response): void
    {
        $limit = $response->header('X-RateLimit-Limit');
        $remaining = $response->header('X-RateLimit-Remaining');
        $reset = $response->header('X-RateLimit-Reset');

        if ($limit !== null || $remaining !== null) {
            Cache::put('mxroute_rate_limit_status', [
                'limit' => (int) $limit,
                'remaining' => (int) $remaining,
                'reset_timestamp' => (int) $reset,
                'updated_at' => now()->toIso8601String(),
            ], now()->addMinutes(5));
        }
    }

    /**
     * Get current cached rate limit status for dashboard display.
     */
    public function getRateLimitStatus(): ?array
    {
        return Cache::get('mxroute_rate_limit_status');
    }

    /*
    |--------------------------------------------------------------------------
    | Domains API
    |--------------------------------------------------------------------------
    */

    /**
     * List all domains on the account.
     * GET /domains
     */
    public function listDomains(): array
    {
        $res = $this->request('GET', '/domains');

        return $res['data'] ?? [];
    }

    /**
     * Add a new domain.
     * POST /domains
     */
    public function createDomain(string $domain): array
    {
        $res = $this->request('POST', '/domains', ['domain' => strtolower(trim($domain))]);

        return $res['data'] ?? [];
    }

    /**
     * Get domain details.
     * GET /domains/{domain}
     */
    public function getDomain(string $domain): array
    {
        $res = $this->request('GET', "/domains/{$domain}");

        return $res['data'] ?? [];
    }

    /**
     * Delete domain.
     * DELETE /domains/{domain}
     */
    public function deleteDomain(string $domain): bool
    {
        $res = $this->request('DELETE', "/domains/{$domain}");

        return ! empty($res['success']);
    }

    /**
     * Set mail hosting status for a domain.
     * PATCH /domains/{domain}/mail-status
     */
    public function setMailHostingStatus(string $domain, bool $enabled): bool
    {
        $res = $this->request('PATCH', "/domains/{$domain}/mail-status", ['enabled' => $enabled]);

        return ! empty($res['success']);
    }

    /*
    |--------------------------------------------------------------------------
    | Email Accounts API
    |--------------------------------------------------------------------------
    */

    /**
     * List email accounts for domain.
     * GET /domains/{domain}/email-accounts
     */
    public function listEmailAccounts(string $domain): array
    {
        $res = $this->request('GET', "/domains/{$domain}/email-accounts");

        return $res['data'] ?? [];
    }

    /**
     * Get specific email account details.
     * GET /domains/{domain}/email-accounts/{user}
     */
    public function getEmailAccount(string $domain, string $user): array
    {
        $res = $this->request('GET', "/domains/{$domain}/email-accounts/{$user}");

        return $res['data'] ?? [];
    }

    /**
     * Create email account.
     * POST /domains/{domain}/email-accounts
     */
    public function createEmailAccount(
        string $domain,
        string $username,
        string $password,
        int $quota = 1024,
        int $limit = 9600
    ): array {
        $payload = [
            'username' => trim($username),
            'password' => $password,
            'quota' => $quota,
            'limit' => min(9600, max(1, $limit)),
        ];

        $res = $this->request('POST', "/domains/{$domain}/email-accounts", $payload);

        return $res['data'] ?? ['success' => true];
    }

    /**
     * Update email account.
     * PATCH /domains/{domain}/email-accounts/{user}
     */
    public function updateEmailAccount(
        string $domain,
        string $user,
        ?string $password = null,
        ?int $quota = null,
        ?int $limit = null
    ): array {
        $payload = [];

        if ($password !== null && trim($password) !== '') {
            $payload['password'] = $password;
        }

        if ($quota !== null) {
            $payload['quota'] = (int) $quota;
        }

        if ($limit !== null) {
            $payload['limit'] = min(9600, max(1, (int) $limit));
        }

        $res = $this->request('PATCH', "/domains/{$domain}/email-accounts/{$user}", $payload);

        return $res['data'] ?? ['success' => true];
    }

    /**
     * Delete email account.
     * DELETE /domains/{domain}/email-accounts/{user}
     */
    public function deleteEmailAccount(string $domain, string $user): bool
    {
        $res = $this->request('DELETE', "/domains/{$domain}/email-accounts/{$user}");

        return ! empty($res['success']);
    }

    /*
    |--------------------------------------------------------------------------
    | Email Forwarders API
    |--------------------------------------------------------------------------
    */

    /**
     * List email forwarders for domain.
     * GET /domains/{domain}/forwarders
     */
    public function listForwarders(string $domain): array
    {
        $res = $this->request('GET', "/domains/{$domain}/forwarders");

        return $res['data'] ?? [];
    }

    /**
     * Create email forwarder.
     * POST /domains/{domain}/forwarders
     */
    public function createForwarder(string $domain, string $alias, array $destinations): array
    {
        $payload = [
            'alias' => trim($alias),
            'destinations' => array_values(array_map('trim', $destinations)),
        ];

        $res = $this->request('POST', "/domains/{$domain}/forwarders", $payload);

        return $res['data'] ?? ['success' => true];
    }

    /**
     * Delete email forwarder.
     * DELETE /domains/{domain}/forwarders/{alias}
     */
    public function deleteForwarder(string $domain, string $alias): bool
    {
        $res = $this->request('DELETE', "/domains/{$domain}/forwarders/{$alias}");

        return ! empty($res['success']);
    }

    /*
    |--------------------------------------------------------------------------
    | Quota API
    |--------------------------------------------------------------------------
    */

    /**
     * Get account disk usage and quota.
     * GET /quota
     */
    public function getQuota(): array
    {
        return $this->request('GET', '/quota');
    }

    /**
     * Get per-email disk usage.
     * GET /quota/email
     */
    public function getEmailQuota(): array
    {
        return $this->request('GET', '/quota/email');
    }

    /*
    |--------------------------------------------------------------------------
    | DNS Info API
    |--------------------------------------------------------------------------
    */

    /**
     * Get DNS configuration info for a domain.
     * GET /domains/{domain}/dns
     */
    public function getDnsInfo(string $domain): array
    {
        $res = $this->request('GET', "/domains/{$domain}/dns");

        return $res['data'] ?? [];
    }

    /*
    |--------------------------------------------------------------------------
    | Catch-All API
    |--------------------------------------------------------------------------
    */

    /**
     * Get catch-all setting for a domain.
     * GET /domains/{domain}/catch-all
     */
    public function getCatchAll(string $domain): array
    {
        $res = $this->request('GET', "/domains/{$domain}/catch-all");

        return $res['data'] ?? [];
    }

    /**
     * Update catch-all setting for a domain.
     * PATCH /domains/{domain}/catch-all
     */
    public function setCatchAll(string $domain, string $type, ?string $address = null): array
    {
        $payload = ['type' => $type];
        if ($type === 'address' && $address) {
            $payload['address'] = trim($address);
        }

        $res = $this->request('PATCH', "/domains/{$domain}/catch-all", $payload);

        return $res['data'] ?? ['success' => true];
    }
}
