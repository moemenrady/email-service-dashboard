<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EmailAccountTest extends TestCase
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

    public function test_can_list_email_accounts_for_assigned_domain(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts" => Http::response([
                'success' => true,
                'data' => [
                    [
                        'username' => 'info',
                        'email' => "info@{$this->domain}",
                        'quota' => 1024,
                        'usage' => 50.2,
                        'limit' => 9600,
                        'sent' => 20,
                        'suspended' => false,
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->user)->get('/emails');

        $response->assertStatus(200);
        $response->assertSee('info@'.$this->domain);
        $response->assertSee('1024 MB');
    }

    public function test_can_create_email_account_with_proper_proxy_headers(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts" => Http::response([
                'success' => true,
                'data' => [
                    'username' => 'contact',
                    'email' => "contact@{$this->domain}",
                ],
            ], 201),
        ]);

        $response = $this->actingAs($this->user)->post('/emails', [
            'username' => 'contact',
            'password' => 'SecurePass1234!',
            'quota' => 2048,
            'limit' => 5000,
        ]);

        $response->assertRedirect('/emails');
        $response->assertSessionHas('success');

        // Verify that MXroute proxy headers were sent from backend
        Http::assertSent(function (Request $request) {
            return $request->url() === "https://api.mxroute.com/domains/{$this->domain}/email-accounts"
                && $request->method() === 'POST'
                && $request->hasHeader('X-Server', config('mxroute.server'))
                && $request->hasHeader('X-Username', config('mxroute.username'))
                && $request->hasHeader('X-API-Key', config('mxroute.api_key'))
                && $request['username'] === 'contact'
                && $request['quota'] === 2048
                && $request['limit'] === 5000;
        });
    }

    public function test_create_email_validates_password_length_min_8_and_max_72(): void
    {
        // Too short (< 8 chars)
        $shortResponse = $this->actingAs($this->user)->post('/emails', [
            'username' => 'shortpass',
            'password' => '1234567',
        ]);
        $shortResponse->assertSessionHasErrors(['password']);

        // Too long (> 72 chars)
        $longPass = str_repeat('A', 73);
        $longResponse = $this->actingAs($this->user)->post('/emails', [
            'username' => 'longpass',
            'password' => $longPass,
        ]);
        $longResponse->assertSessionHasErrors(['password']);
    }

    public function test_create_email_validates_username_allowed_characters(): void
    {
        $response = $this->actingAs($this->user)->post('/emails', [
            'username' => 'bad user name!@#',
            'password' => 'ValidPassword123!',
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_create_email_handles_conflict_without_leaking_raw_error(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts" => Http::response([
                'success' => false,
                'error' => [
                    'code' => 'CONFLICT',
                    'message' => 'Mailbox already exists on fusion.mxrouting.net',
                ],
            ], 409),
        ]);

        $response = $this->actingAs($this->user)->post('/emails', [
            'username' => 'existinguser',
            'password' => 'Password123!',
        ]);

        $response->assertSessionHasErrors(['username']);
        // Verify no raw upstream server name leaked
        $this->assertStringNotContainsString('fusion.mxrouting.net', session('errors')->first('username'));
    }

    public function test_can_update_email_account(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts/contact" => Http::response([
                'success' => true,
                'data' => [],
            ], 200),
        ]);

        $response = $this->actingAs($this->user)->patch('/emails/contact', [
            'password' => 'NewSecurePassword123!',
            'quota' => 4096,
            'limit' => 8000,
        ]);

        $response->assertRedirect('/emails');
        $response->assertSessionHas('success');

        Http::assertSent(function (Request $request) {
            return $request->url() === "https://api.mxroute.com/domains/{$this->domain}/email-accounts/contact"
                && $request->method() === 'PATCH'
                && $request['quota'] === 4096
                && $request['limit'] === 8000;
        });
    }

    public function test_can_delete_email_account(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts/olduser" => Http::response(null, 204),
        ]);

        $response = $this->actingAs($this->user)->delete('/emails/olduser');

        $response->assertRedirect('/emails');
        $response->assertSessionHas('success');

        Http::assertSent(function (Request $request) {
            return $request->url() === "https://api.mxroute.com/domains/{$this->domain}/email-accounts/olduser"
                && $request->method() === 'DELETE';
        });
    }

    public function test_tenant_isolation_prevents_managing_other_domain(): void
    {
        $otherUser = User::factory()->create([
            'role' => 'client',
            'assigned_domain' => 'victimdomain.com',
        ]);

        // Attempting to post or manage with someone else's domain in header/request
        $response = $this->actingAs($otherUser)
            ->withSession(['active_domain' => 'attacker-target.com'])
            ->get('/emails');

        $response->assertStatus(403);
    }
}
