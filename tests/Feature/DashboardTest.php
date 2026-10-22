<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'client',
            'assigned_domain' => 'clientdomain.com',
        ]);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_dashboard_with_mocked_data(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/clientdomain.com/email-accounts' => Http::response([
                'success' => true,
                'data' => [
                    [
                        'username' => 'sales',
                        'email' => 'sales@clientdomain.com',
                        'quota' => 2048,
                        'usage' => 120.5,
                        'limit' => 9600,
                        'sent' => 15,
                        'suspended' => false,
                    ],
                ],
            ], 200, ['X-RateLimit-Limit' => '200', 'X-RateLimit-Remaining' => '199']),

            'https://api.mxroute.com/domains/clientdomain.com/forwarders' => Http::response([
                'success' => true,
                'data' => [
                    [
                        'alias' => 'support',
                        'email' => 'support@clientdomain.com',
                        'destinations' => ['team@otherdomain.com'],
                    ],
                ],
            ], 200),

            'https://api.mxroute.com/quota' => Http::response([
                'username' => 'testuser',
                'total_used' => 126353408, // ~120.5 MB
                'total_limit' => 2147483648, // 2048 MB
                'percent_used' => 5.8,
                'breakdown' => ['email' => 126353408],
            ], 200),

            'https://api.mxroute.com/domains/clientdomain.com/dns' => Http::response([
                'success' => true,
                'data' => [
                    'mx_records' => [
                        ['priority' => 10, 'hostname' => 'fusion.mxrouting.net'],
                    ],
                    'spf' => [
                        'type' => 'TXT',
                        'name' => '@',
                        'value' => 'v=spf1 include:mxroute.com -all',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('clientdomain.com');
        $response->assertSee('sales@clientdomain.com');
        $response->assertSee('Moemen Workspace');
    }

    public function test_dashboard_handles_rate_limiting_gracefully(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/clientdomain.com/email-accounts' => Http::response([
                'success' => false,
                'error' => [
                    'code' => 'RATE_LIMITED',
                    'message' => 'Too many requests.',
                ],
            ], 429, ['Retry-After' => '45']),

            'https://api.mxroute.com/*' => Http::response(['success' => true, 'data' => []], 200),
        ]);

        $response = $this->actingAs($this->user)->get('/dashboard');

        // Dashboard should gracefully display warning rather than crashing with 500
        $response->assertStatus(200);
        $response->assertSee('Rate limit');
    }

    public function test_user_cannot_switch_to_unauthorized_domain(): void
    {
        $response = $this->actingAs($this->user)->post('/domains/switch', [
            'domain' => 'unauthorized-domain.com',
        ]);

        $response->assertStatus(403);
    }
}
