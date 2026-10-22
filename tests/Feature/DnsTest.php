<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DnsTest extends TestCase
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

    public function test_can_view_dns_configuration(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/dns" => Http::response([
                'success' => true,
                'data' => [
                    'mx_records' => [
                        ['priority' => 10, 'hostname' => 'fusion.mxrouting.net'],
                        ['priority' => 20, 'hostname' => 'fusion-relay.mxrouting.net'],
                    ],
                    'spf' => [
                        'type' => 'TXT',
                        'name' => '@',
                        'value' => 'v=spf1 include:mxroute.com -all',
                    ],
                    'dkim' => [
                        'type' => 'TXT',
                        'name' => 'x._domainkey',
                        'value' => 'v=DKIM1; k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC...',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->user)->get('/domain/dns');

        $response->assertStatus(200);
        $response->assertSee('Mail Exchanger (MX) Records');
        $response->assertSee('v=spf1 include:mxroute.com -all');
        $response->assertSee('x._domainkey');
    }
}
