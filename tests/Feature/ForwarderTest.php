<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ForwarderTest extends TestCase
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

    public function test_can_list_forwarders(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/forwarders" => Http::response([
                'success' => true,
                'data' => [
                    [
                        'alias' => 'support',
                        'email' => "support@{$this->domain}",
                        'destinations' => ['agent1@gmail.com', 'agent2@gmail.com'],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->user)->get('/forwarders');

        $response->assertStatus(200);
        $response->assertSee('support@' . $this->domain);
        $response->assertSee('agent1@gmail.com');
        $response->assertSee('agent2@gmail.com');
    }

    public function test_can_create_forwarder_with_multiple_destinations(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/forwarders" => Http::response([
                'success' => true,
                'data' => [
                    'alias' => 'billing',
                    'destinations' => ['finance@external.com', 'ceo@external.com'],
                ],
            ], 201),
        ]);

        $response = $this->actingAs($this->user)->post('/forwarders', [
            'alias' => 'billing',
            'destinations' => 'finance@external.com, ceo@external.com',
        ]);

        $response->assertRedirect('/forwarders');
        $response->assertSessionHas('success');

        Http::assertSent(function (Request $request) {
            return $request->url() === "https://api.mxroute.com/domains/{$this->domain}/forwarders"
                && $request->method() === 'POST'
                && $request['alias'] === 'billing'
                && $request['destinations'] === ['finance@external.com', 'ceo@external.com'];
        });
    }

    public function test_can_create_forwarder_with_special_destinations_blackhole_and_fail(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/forwarders" => Http::response([
                'success' => true,
            ], 201),
        ]);

        // Blackhole
        $response1 = $this->actingAs($this->user)->post('/forwarders', [
            'alias' => 'spam-trap',
            'destinations' => ':blackhole:',
        ]);
        $response1->assertRedirect('/forwarders');
        $response1->assertSessionHas('success');

        // Fail
        $response2 = $this->actingAs($this->user)->post('/forwarders', [
            'alias' => 'former-employee',
            'destinations' => ':fail:',
        ]);
        $response2->assertRedirect('/forwarders');
        $response2->assertSessionHas('success');
    }

    public function test_create_forwarder_rejects_invalid_destination(): void
    {
        $response = $this->actingAs($this->user)->post('/forwarders', [
            'alias' => 'newsletter',
            'destinations' => 'not-an-email-address',
        ]);

        $response->assertSessionHasErrors(['destinations.0']);
    }

    public function test_can_delete_forwarder(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/forwarders/oldalias" => Http::response(null, 204),
        ]);

        $response = $this->actingAs($this->user)->delete('/forwarders/oldalias');

        $response->assertRedirect('/forwarders');
        $response->assertSessionHas('success');

        Http::assertSent(function (Request $request) {
            return $request->url() === "https://api.mxroute.com/domains/{$this->domain}/forwarders/oldalias"
                && $request->method() === 'DELETE';
        });
    }
}
