<?php

namespace Tests\Unit;

use App\Exceptions\MxRouteApiException;
use App\Exceptions\MxRouteRateLimitException;
use App\Exceptions\MxRouteValidationException;
use App\Services\MxRouteApiService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MxRouteApiServiceTest extends TestCase
{
    protected MxRouteApiService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new MxRouteApiService(
            server: 'fusion.mxrouting.net',
            username: 'ryvaaefaya',
            apiKey: 'test-api-key-12345',
            baseUrl: 'https://api.mxroute.com'
        );
    }

    public function test_service_sends_required_auth_headers(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains' => Http::response([
                'success' => true,
                'data' => ['testdomain.com'],
            ], 200),
        ]);

        $domains = $this->service->listDomains();

        $this->assertEquals(['testdomain.com'], $domains);

        Http::assertSent(function (Request $request) {
            return $request->hasHeader('X-Server', 'fusion.mxrouting.net')
                && $request->hasHeader('X-Username', 'ryvaaefaya')
                && $request->hasHeader('X-API-Key', 'test-api-key-12345')
                && $request->hasHeader('Accept', 'application/json');
        });
    }

    public function test_service_maps_validation_error_to_exception(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/test.com/email-accounts' => Http::response([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Password must meet complexity requirements',
                    'field' => 'password',
                ],
            ], 422),
        ]);

        $this->expectException(MxRouteValidationException::class);
        $this->expectExceptionMessage('Password must meet complexity requirements');

        $this->service->createEmailAccount('test.com', 'user', 'weakpass');
    }

    public function test_service_maps_rate_limited_to_rate_limit_exception_with_retry_after(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/test.com/email-accounts' => Http::response([
                'success' => false,
                'error' => [
                    'code' => 'RATE_LIMITED',
                    'message' => 'Too many requests.',
                ],
            ], 429, ['Retry-After' => '35']),
        ]);

        try {
            $this->service->createEmailAccount('test.com', 'user', 'ValidPass123!');
            $this->fail('Expected MxRouteRateLimitException was not thrown.');
        } catch (MxRouteRateLimitException $e) {
            $this->assertEquals(35, $e->getRetryAfter());
            $this->assertEquals(429, $e->getStatusCode());
            $this->assertEquals('RATE_LIMITED', $e->getErrorCode());
        }
    }

    public function test_service_maps_conflict_error(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/test.com/email-accounts' => Http::response([
                'success' => false,
                'error' => [
                    'code' => 'CONFLICT',
                    'message' => 'Account already exists',
                ],
            ], 409),
        ]);

        $this->expectException(MxRouteApiException::class);

        $this->service->createEmailAccount('test.com', 'existing', 'ValidPass123!');
    }

    public function test_service_sanitizes_upstream_infrastructure_names_from_messages(): void
    {
        $raw = 'Error from fusion.mxrouting.net via mxroute DirectAdmin backend: mailbox unavailable';
        $sanitized = MxRouteApiException::sanitizeMessage($raw);

        $this->assertStringNotContainsString('fusion.mxrouting.net', $sanitized);
        $this->assertStringNotContainsString('DirectAdmin', $sanitized);
        $this->assertStringNotContainsString('mxroute', strtolower($sanitized));
        $this->assertStringContainsString('Mail Server', $sanitized);
        $this->assertStringContainsString('Mail System', $sanitized);
    }
}
