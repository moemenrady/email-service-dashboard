<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_visiting_home_are_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_client_visiting_home_is_redirected_to_emails(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'assigned_domain' => 'albrolosy.com',
        ]);

        $response = $this->actingAs($client)->get('/');

        $response->assertRedirect(route('emails.index'));
    }

    public function test_authenticated_admin_visiting_home_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/');

        $response->assertRedirect(route('admin.dashboard'));
    }
}
