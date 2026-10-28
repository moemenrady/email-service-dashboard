<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ActivityLogTest extends TestCase
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
            'password' => Hash::make('AdminSecret123!'),
            'role' => 'admin',
            'assigned_domain' => 'albrolosy.com',
        ]);

        $this->client = User::factory()->create([
            'name' => 'Al Brolosy Management',
            'email' => 'client@albrolosy.com',
            'password' => Hash::make('ClientSecret123!'),
            'role' => 'client',
            'assigned_domain' => 'albrolosy.com',
            'max_emails' => 10,
            'storage_limit_mb' => 10240,
        ]);
    }

    public function test_login_creates_activity_log_with_device_and_ip(): void
    {
        $response = $this->withServerVariables([
            'REMOTE_ADDR' => '197.35.40.10',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1',
        ])->post('/login', [
            'email' => 'client@albrolosy.com',
            'password' => 'ClientSecret123!',
        ]);

        $response->assertRedirect('/emails');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->client->id,
            'user_email' => 'client@albrolosy.com',
            'action_type' => 'login',
            'ip_address' => '197.35.40.10',
            'device_type' => 'Mobile',
            'platform' => 'iOS (iPhone)',
        ]);
    }

    public function test_logout_creates_activity_log(): void
    {
        $response = $this->actingAs($this->client)->post('/logout');

        $response->assertRedirect('/login');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->client->id,
            'action_type' => 'logout',
        ]);
    }

    public function test_page_view_is_logged_for_authenticated_client(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/albrolosy.com/email-accounts' => Http::response([
                'success' => true,
                'data' => [],
            ], 200),
        ]);

        $response = $this->actingAs($this->client)->get('/emails');
        $response->assertOk();

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->client->id,
            'action_type' => 'page_view',
            'action_title' => 'زيارة صفحة: إدارة حسابات البريد الإلكتروني',
        ]);
    }

    public function test_email_creation_is_logged(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/albrolosy.com/email-accounts' => Http::sequence()
                ->push(['success' => true, 'data' => []], 200)
                ->push(['success' => true, 'data' => []], 200),
        ]);

        $response = $this->actingAs($this->client)->post('/emails', [
            'username' => 'finance',
            'password' => 'Finance@Pass2026',
            'quota' => 2048,
        ]);

        $response->assertRedirect('/emails');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->client->id,
            'action_type' => 'email_create',
            'action_title' => 'إنشاء حساب بريد جديد: finance@albrolosy.com',
        ]);
    }

    public function test_email_update_is_logged(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/albrolosy.com/email-accounts/finance' => Http::response([
                'success' => true,
                'message' => 'Updated',
            ], 200),
        ]);

        $response = $this->actingAs($this->client)->patch('/emails/finance', [
            'password' => 'NewFinancePass2026!',
            'quota' => 4096,
        ]);

        $response->assertRedirect('/emails');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->client->id,
            'action_type' => 'email_update',
            'action_title' => 'تعديل إعدادات البريد: finance@albrolosy.com',
        ]);
    }

    public function test_email_deletion_is_logged(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/albrolosy.com/email-accounts/finance' => Http::response([
                'success' => true,
                'message' => 'Deleted',
            ], 200),
        ]);

        $response = $this->actingAs($this->client)->delete('/emails/finance');

        $response->assertRedirect('/emails');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->client->id,
            'action_type' => 'email_delete',
            'action_title' => 'حذف حساب البريد نهائياً: finance@albrolosy.com',
        ]);
    }

    public function test_forwarder_creation_and_deletion_are_logged(): void
    {
        Http::fake([
            'https://api.mxroute.com/domains/albrolosy.com/forwarders' => Http::response([
                'success' => true,
                'message' => 'Created',
            ], 200),
            'https://api.mxroute.com/domains/albrolosy.com/forwarders/sales' => Http::response([
                'success' => true,
                'message' => 'Deleted',
            ], 200),
        ]);

        // Create
        $response = $this->actingAs($this->client)->post('/forwarders', [
            'alias' => 'sales',
            'destinations' => ['director@albrolosy.com'],
        ]);
        $response->assertRedirect('/forwarders');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->client->id,
            'action_type' => 'forwarder_create',
            'action_title' => 'إنشاء إعادة توجيه من sales@albrolosy.com إلى [director@albrolosy.com]',
        ]);

        // Delete
        $deleteResponse = $this->actingAs($this->client)->delete('/forwarders/sales');
        $deleteResponse->assertRedirect('/forwarders');

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->client->id,
            'action_type' => 'forwarder_delete',
            'action_title' => 'حذف إعادة التوجيه: sales@albrolosy.com',
        ]);
    }

    public function test_pdf_share_endpoint_logs_action(): void
    {
        $response = $this->actingAs($this->client)->postJson('/emails/ceo/log-share');

        $response->assertOk()
            ->assertJson(['success' => true, 'email' => 'ceo@albrolosy.com']);

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->client->id,
            'action_type' => 'pdf_share',
            'action_title' => 'مشاركة بطاقة اعتماد الحساب (PDF) للبريد [ceo@albrolosy.com]',
        ]);
    }

    public function test_password_change_is_logged(): void
    {
        $response = $this->actingAs($this->client)->put('/password', [
            'current_password' => 'ClientSecret123!',
            'password' => 'BrandNewClientPassword2026!',
            'password_confirmation' => 'BrandNewClientPassword2026!',
        ]);

        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->client->id,
            'action_type' => 'password_change',
            'action_title' => 'تغيير كلمة المرور الخاصة بالحساب',
        ]);
    }

    public function test_client_cannot_access_admin_activities(): void
    {
        $response = $this->actingAs($this->client)->get('/admin/activities');
        $response->assertStatus(403);
    }

    public function test_admin_can_view_activity_logs_and_filter(): void
    {
        ActivityLog::create([
            'user_id' => $this->client->id,
            'user_name' => $this->client->name,
            'user_email' => $this->client->email,
            'user_role' => 'client',
            'assigned_domain' => 'albrolosy.com',
            'action_type' => 'login',
            'action_title' => 'تسجيل دخول إلى النظام',
            'ip_address' => '10.0.0.1',
            'device_type' => 'Desktop',
            'browser' => 'Chrome 124',
            'platform' => 'Windows 11',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/activities');
        $response->assertOk();
        $response->assertSee('سجل النشاطات والتحليلات');
        $response->assertSee('Al Brolosy Management');
        $response->assertSee('تسجيل دخول إلى النظام');

        // Test filter by action type
        $filterResponse = $this->actingAs($this->admin)->get('/admin/activities?action_type=login');
        $filterResponse->assertOk();
        $filterResponse->assertSee('Al Brolosy Management');

        // Test search
        $searchResponse = $this->actingAs($this->admin)->get('/admin/activities?search=10.0.0.1');
        $searchResponse->assertOk();
        $searchResponse->assertSee('Al Brolosy Management');
    }

    public function test_admin_can_export_activities_csv(): void
    {
        ActivityLog::create([
            'user_id' => $this->client->id,
            'user_name' => $this->client->name,
            'user_email' => $this->client->email,
            'user_role' => 'client',
            'assigned_domain' => 'albrolosy.com',
            'action_type' => 'email_create',
            'action_title' => 'إنشاء حساب بريد جديد: hr@albrolosy.com',
            'ip_address' => '192.168.1.50',
            'device_type' => 'Desktop',
            'browser' => 'Firefox 125',
            'platform' => 'Linux',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/activities/export');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('moemen-team-activity-logs-', (string) $response->headers->get('Content-Disposition'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('hr@albrolosy.com', $content);
        $this->assertStringContainsString('إنشاء بريد', $content);
        $this->assertStringContainsString('Firefox 125', $content);
    }
}
