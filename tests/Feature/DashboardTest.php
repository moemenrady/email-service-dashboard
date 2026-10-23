<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $client;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = User::factory()->create([
            'role' => 'client',
            'assigned_domain' => 'albrolosy.com',
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'assigned_domain' => 'albrolosy.com',
        ]);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_client_is_redirected_from_dashboard_to_emails(): void
    {
        $response = $this->actingAs($this->client)->get('/dashboard');
        $response->assertRedirect(route('emails.index'));
    }

    public function test_admin_is_redirected_from_dashboard_to_admin_panel(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_user_cannot_switch_to_unauthorized_domain(): void
    {
        $response = $this->actingAs($this->client)->post('/domains/switch', [
            'domain' => 'unauthorized-domain.com',
        ]);

        $response->assertStatus(403);
    }
}
