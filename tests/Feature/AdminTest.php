<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Moemen Admin',
            'email' => 'admin@moemen.team',
            'role' => 'admin',
            'assigned_domain' => 'albrolosy.com',
        ]);

        $this->client = User::factory()->create([
            'name' => 'Client One',
            'email' => 'client@albrolosy.com',
            'role' => 'client',
            'assigned_domain' => 'albrolosy.com',
        ]);
    }

    public function test_guests_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_clients_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->client)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_view_admin_dashboard_with_live_domains(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains' => Http::response([
                'success' => true,
                'data' => [
                    ['domain' => 'albrolosy.com'],
                    ['domain' => 'seconddomain.com'],
                ],
            ], 200),
            'https://api.mxroute.com/domains/albrolosy.com/email-accounts' => Http::response([
                'success' => true,
                'data' => [
                    ['username' => 'info', 'quota' => 1024, 'limit' => 9600],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('لوحة تحكم الإدارة');
        $response->assertSee('albrolosy.com');
        $response->assertSee('Client One');
    }

    public function test_admin_can_create_new_client(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/clients', [
            'name' => 'New Client',
            'email' => 'newclient@example.com',
            'password' => 'Password123!',
            'assigned_domain' => 'albrolosy.com',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'newclient@example.com',
            'role' => 'client',
            'assigned_domain' => 'albrolosy.com',
        ]);
    }

    public function test_admin_can_update_client(): void
    {
        $response = $this->actingAs($this->admin)->patch("/admin/clients/{$this->client->id}", [
            'name' => 'Updated Client Name',
            'email' => 'client@albrolosy.com',
            'assigned_domain' => 'newdomain.com',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->client->id,
            'name' => 'Updated Client Name',
            'assigned_domain' => 'newdomain.com',
        ]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->admin)->delete("/admin/clients/{$this->admin->id}");

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_delete_client(): void
    {
        $response = $this->actingAs($this->admin)->delete("/admin/clients/{$this->client->id}");

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $this->client->id,
        ]);
    }

    public function test_admin_can_add_domain_via_api(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains' => Http::response([
                'success' => true,
                'data' => ['domain' => 'brandnewdomain.com'],
            ], 200),
        ]);

        $response = $this->actingAs($this->admin)->post('/admin/domains', [
            'domain' => 'brandnewdomain.com',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');
    }

    public function test_admin_can_delete_domain_via_api(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/olddomain.com' => Http::response([
                'success' => true,
            ], 200),
        ]);

        $response = $this->actingAs($this->admin)->delete('/admin/domains/olddomain.com');

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');
    }

    public function test_admin_can_switch_active_domain(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/domains/switch', [
            'domain' => 'albrolosy.com',
        ]);

        $response->assertRedirect(route('emails.index'));
        $this->assertEquals('albrolosy.com', session('active_domain'));
    }
}
