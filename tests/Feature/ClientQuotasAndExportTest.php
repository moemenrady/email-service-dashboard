<?php

namespace Tests\Feature;

use App\Models\MailboxCredential;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ClientQuotasAndExportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $client;

    protected string $domain = 'albrolosy.com';

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Moemen Admin',
            'email' => 'admin@moemen.team',
            'role' => 'admin',
            'assigned_domain' => $this->domain,
        ]);

        $this->client = User::factory()->create([
            'name' => 'Albrolosy Client',
            'email' => 'client@albrolosy.com',
            'role' => 'client',
            'assigned_domain' => $this->domain,
            'max_emails' => 2,
            'storage_limit_mb' => 2048,
        ]);
    }

    public function test_admin_can_set_quotas_when_creating_client(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/clients', [
            'name' => 'New Client',
            'email' => 'new@test.com',
            'password' => 'secret12345',
            'assigned_domain' => 'newdomain.com',
            'max_emails' => 5,
            'storage_limit_mb' => 5120,
        ]);

        $response->assertRedirect('/admin');
        $response->assertSessionHas('new_client_credentials');

        $this->assertDatabaseHas('users', [
            'email' => 'new@test.com',
            'max_emails' => 5,
            'storage_limit_mb' => 5120,
        ]);
    }

    public function test_admin_can_update_client_quotas(): void
    {
        $response = $this->actingAs($this->admin)->patch("/admin/clients/{$this->client->id}", [
            'name' => 'Updated Client',
            'email' => 'client@albrolosy.com',
            'assigned_domain' => $this->domain,
            'max_emails' => 10,
            'storage_limit_mb' => 10240,
        ]);

        $response->assertRedirect('/admin');

        $this->assertDatabaseHas('users', [
            'id' => $this->client->id,
            'max_emails' => 10,
            'storage_limit_mb' => 10240,
        ]);
    }

    public function test_client_cannot_exceed_max_emails_quota(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts" => Http::response([
                'success' => true,
                'data' => [
                    ['username' => 'acc1', 'email' => "acc1@{$this->domain}", 'quota' => 500, 'usage' => 10],
                    ['username' => 'acc2', 'email' => "acc2@{$this->domain}", 'quota' => 500, 'usage' => 20],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->client)->post('/emails', [
            'username' => 'acc3',
            'password' => 'Password123!',
            'quota' => 500,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_client_cannot_exceed_storage_limit_quota(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts" => Http::response([
                'success' => true,
                'data' => [
                    ['username' => 'acc1', 'email' => "acc1@{$this->domain}", 'quota' => 1800, 'usage' => 10],
                ],
            ], 200),
        ]);

        // Client limit is 2048 MB. Existing is 1800 MB. Creating 500 MB exceeds 2048 MB.
        $response = $this->actingAs($this->client)->post('/emails', [
            'username' => 'acc2',
            'password' => 'Password123!',
            'quota' => 500,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_admin_can_export_client_pdf(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts" => Http::response([
                'success' => true,
                'data' => [
                    ['username' => 'info', 'email' => "info@{$this->domain}", 'quota' => 1024, 'usage' => 50, 'suspended' => false],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/clients/{$this->client->id}/export-pdf");

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_client_can_export_all_emails_pdf(): void
    {
        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts" => Http::response([
                'success' => true,
                'data' => [
                    ['username' => 'info', 'email' => "info@{$this->domain}", 'quota' => 1024, 'usage' => 50, 'suspended' => false],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->client)->get('/emails/export-all-pdf');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_client_can_export_single_email_pdf(): void
    {
        MailboxCredential::storeOrUpdate($this->domain, 'info', 'PassSecure987!');

        Http::fake([
            "https://api.mxroute.com/domains/{$this->domain}/email-accounts" => Http::response([
                'success' => true,
                'data' => [
                    ['username' => 'info', 'email' => "info@{$this->domain}", 'quota' => 1024, 'usage' => 50, 'suspended' => false],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->client)->get('/emails/info/export-pdf');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_guide_page_loads_for_authenticated_users(): void
    {
        $response = $this->actingAs($this->client)->get('/guide');

        $response->assertStatus(200);
        $response->assertSee('https://mail.albrolosy.com');
        $response->assertSee('دليل استخدام وإعداد البريد الإلكتروني');
        $response->assertDontSee('mxroute');
    }
}
