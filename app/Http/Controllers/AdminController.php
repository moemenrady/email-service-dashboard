<?php

namespace App\Http\Controllers;

use App\Exceptions\MxRouteApiException;
use App\Models\User;
use App\Services\DocumentPdfService;
use App\Services\MxRouteApiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard: overview, client accounts, domains from MXroute.
     */
    public function index(Request $request, MxRouteApiService $api): View
    {
        $users = User::latest()->get();
        $clientsCount = User::where('role', 'client')->count();

        $domains = [];
        $apiError = null;

        try {
            $domains = $api->listDomains();
        } catch (MxRouteApiException $e) {
            $apiError = $e->getMessage();
            Log::warning("Admin dashboard: unable to fetch domains from MXroute: {$e->getMessage()}");
            // Fallback to default domain if API temporarily unavailable
            $defaultDomain = config('mxroute.default_domain', 'albrolosy.com');
            $domains = [$defaultDomain];
        } catch (\Exception $e) {
            $apiError = 'تعذر الاتصال بخادم البريد حالياً.';
            Log::error("Admin dashboard unexpected error: {$e->getMessage()}");
        }

        // Calculate mailboxes count for active domain if available
        $activeDomain = session('active_domain', config('mxroute.default_domain', 'albrolosy.com'));
        $mailboxesCount = 0;
        try {
            $emails = $api->listEmailAccounts($activeDomain);
            $mailboxesCount = count($emails);
        } catch (\Exception $e) {
            // Non-blocking
        }

        $rateLimitStatus = $api->getRateLimitStatus();

        return view('admin.dashboard', [
            'users' => $users,
            'clientsCount' => $clientsCount,
            'domains' => $domains,
            'domainsCount' => count($domains),
            'mailboxesCount' => $mailboxesCount,
            'activeDomain' => $activeDomain,
            'rateLimitStatus' => $rateLimitStatus,
            'apiError' => $apiError,
        ]);
    }

    /**
     * Create a new client user and assign them a domain with limits.
     */
    public function storeClient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults()],
            'assigned_domain' => ['required', 'string', 'max:255'],
            'max_emails' => ['nullable', 'integer', 'min:1'],
            'storage_limit_mb' => ['nullable', 'integer', 'min:100'],
        ], [
            'name.required' => 'يرجى إدخال اسم العميل.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني للعميل.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل بالفعل.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'assigned_domain.required' => 'يرجى اختيار الدومين المخصص للعميل.',
            'max_emails.min' => 'يجب أن يكون حد الإيميلات 1 على الأقل.',
            'storage_limit_mb.min' => 'يجب ألا تقل المساحة المخصصة عن 100 ميجابايت.',
        ]);

        $maxEmails = ! empty($validated['max_emails']) ? (int) $validated['max_emails'] : null;
        $storageLimitMb = ! empty($validated['storage_limit_mb']) ? (int) $validated['storage_limit_mb'] : null;

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'assigned_domain' => strtolower(trim($validated['assigned_domain'])),
            'max_emails' => $maxEmails,
            'storage_limit_mb' => $storageLimitMb,
        ]);

        $credentialsPayload = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $validated['password'],
            'assigned_domain' => $user->assigned_domain,
            'max_emails' => $user->max_emails ? $user->max_emails.' حسابات' : 'غير محدود (Unlimited)',
            'storage_limit_mb' => $user->storage_limit_mb ? $user->storage_limit_mb.' MB' : 'غير محدود (Unlimited)',
            'login_url' => route('login'),
            'webmail_url' => 'https://mail.albrolosy.com/',
        ];

        return redirect()->route('admin.dashboard')
            ->with('success', 'تم إنشاء حساب العميل وتخصيص الباقة له بنجاح.')
            ->with('new_client_credentials', $credentialsPayload);
    }

    /**
     * Update client details and assigned domain.
     */
    public function updateClient(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', Password::defaults()],
            'assigned_domain' => ['required', 'string', 'max:255'],
            'max_emails' => ['nullable', 'integer', 'min:1'],
            'storage_limit_mb' => ['nullable', 'integer', 'min:100'],
        ], [
            'name.required' => 'يرجى إدخال اسم العميل.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني للعميل.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل لعميل آخر.',
            'assigned_domain.required' => 'يرجى اختيار الدومين المخصص للعميل.',
            'max_emails.min' => 'يجب أن يكون حد الإيميلات 1 على الأقل.',
            'storage_limit_mb.min' => 'يجب ألا تقل المساحة المخصصة عن 100 ميجابايت.',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'assigned_domain' => strtolower(trim($validated['assigned_domain'])),
            'max_emails' => ! empty($validated['max_emails']) ? (int) $validated['max_emails'] : null,
            'storage_limit_mb' => ! empty($validated['storage_limit_mb']) ? (int) $validated['storage_limit_mb'] : null,
        ];

        if (! empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'تم تحديث بيانات العميل وباقته بنجاح.');
    }

    /**
     * Export all client data, mailboxes, and storage to a comprehensive PDF.
     */
    public function exportClientPdf(Request $request, User $user, MxRouteApiService $api): Response|BinaryFileResponse
    {
        $domain = $user->assigned_domain ?: config('mxroute.default_domain', 'albrolosy.com');
        $emails = [];
        $apiError = null;

        try {
            $emails = $api->listEmailAccounts($domain);
        } catch (\Exception $e) {
            $apiError = $e->getMessage();
        }

        $totalUsageMb = 0;
        $totalQuotaMb = 0;
        foreach ($emails as $em) {
            $totalUsageMb += (float) ($em['usage'] ?? 0);
            $totalQuotaMb += (int) ($em['quota'] ?? 0);
        }

        $data = [
            'clientUser' => $user,
            'client' => $user,
            'domain' => $domain,
            'emails' => $emails,
            'mailboxesCount' => count($emails),
            'totalUsageMb' => round($totalUsageMb, 2),
            'totalQuotaMb' => $totalQuotaMb,
            'apiError' => $apiError,
            'webmailUrl' => 'https://mail.albrolosy.com/',
            'serverHost' => 'mail.albrolosy.com',
            'generatedAtFormatted' => now()->translatedFormat('d F Y'),
        ];

        $asDownload = $request->query('view') !== '1' && $request->query('format') !== 'html';

        return DocumentPdfService::render('admin.pdf.client-report', $data, "client-report-{$user->name}-{$domain}.pdf", $asDownload);
    }

    /**
     * Delete a client user account.
     */
    public function destroyClient(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return redirect()->route('admin.dashboard')->with('error', 'لا يمكنك حذف حساب المدير الخاص بك.');
        }

        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'تم حذف حساب العميل بنجاح.');
    }

    /**
     * Add a new domain to the MXroute mail server.
     */
    public function storeDomain(Request $request, MxRouteApiService $api): RedirectResponse
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'regex:/^([a-zA-Z0-9]+(-[a-zA-Z0-9]+)*\.)+[a-zA-Z]{2,}$/'],
        ], [
            'domain.required' => 'يرجى إدخال اسم الدومين.',
            'domain.regex' => 'صيغة الدومين غير صحيحة (مثال: example.com).',
        ]);

        $domain = strtolower(trim($validated['domain']));

        try {
            $api->createDomain($domain);

            return redirect()->route('admin.dashboard')->with('success', "تمت إضافة الدومين [{$domain}] إلى خادم البريد بنجاح.");
        } catch (MxRouteApiException $e) {
            return redirect()->route('admin.dashboard')->with('error', $e->getMessage());
        }
    }

    /**
     * Delete a domain from the MXroute mail server.
     */
    public function deleteDomain(string $domain, MxRouteApiService $api): RedirectResponse
    {
        try {
            $api->deleteDomain($domain);

            return redirect()->route('admin.dashboard')->with('success', "تم حذف الدومين [{$domain}] من خادم البريد.");
        } catch (MxRouteApiException $e) {
            return redirect()->route('admin.dashboard')->with('error', $e->getMessage());
        }
    }

    /**
     * Switch admin's active domain to manage its email accounts.
     */
    public function switchDomain(Request $request): RedirectResponse
    {
        $domain = $request->input('domain');
        if (! empty($domain)) {
            session(['active_domain' => strtolower(trim($domain))]);
        }

        return redirect()->route('emails.index')->with('success', "تم تحويل لوحة الإدارة إلى الدومين [{$domain}].");
    }
}
