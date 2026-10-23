<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\MxRouteApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected string $domain = 'moemen-team.com';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'client',
            'assigned_domain' => $this->domain,
        ]);
    }

    public function test_api_tracks_rate_limit_headers_into_cache(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts" => Http::response([
                'success' => true,
                'data' => [],
            ], 200, [
                'X-RateLimit-Limit' => '200',
                'X-RateLimit-Remaining' => '175',
                'X-RateLimit-Reset' => (string) (time() + 60),
            ]),
        ]);

        $api = app(MxRouteApiService::class);
        $api->listEmailAccounts($this->domain);

        $status = $api->getRateLimitStatus();

        $this->assertNotNull($status);
        $this->assertEquals(200, $status['limit']);
        $this->assertEquals(175, $status['remaining']);
    }

    public function test_post_rate_limited_429_returns_friendly_error_with_retry_after(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts" => Http::response([
                'success' => false,
                'error' => [
                    'code' => 'RATE_LIMITED',
                    'message' => 'Too many write requests to the mail server.',
                ],
            ], 429, [
                'Retry-After' => '50',
            ]),
        ]);

        $response = $this->actingAs($this->user)->from('/emails')->post('/emails', [
            'username' => 'newuser',
            'password' => 'ValidPassword123!',
        ]);

        $response->assertRedirect('/emails');
        $response->assertSessionHas('error');
        $errorMessage = session('error');

        $this->assertStringContainsString('rate limit', strtolower($errorMessage));
        $this->assertStringContainsString('50', $errorMessage);
    }
}
