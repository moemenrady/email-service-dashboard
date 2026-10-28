<?php

namespace App\Http\Controllers;

use App\Exceptions\MxRouteApiException;
use App\Exceptions\MxRouteRateLimitException;
use App\Exceptions\MxRouteValidationException;
use App\Http\Requests\CreateEmailAccountRequest;
use App\Http\Requests\UpdateEmailAccountRequest;
use App\Models\MailboxCredential;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\DocumentPdfService;
use App\Services\MxRouteApiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EmailAccountController extends Controller
{
    /**
     * Display a listing of email accounts for the active domain.
     */
    public function index(Request $request, MxRouteApiService $api): View
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
        $emails = [];
        $error = null;

        try {
            $emails = $api->listEmailAccounts($domain);
        } catch (MxRouteRateLimitException $e) {
            $error = $e->getMessage();
        } catch (MxRouteApiException $e) {
            $error = $e->getMessage();
        }

        // Get client limits if available
        $clientUser = $request->user()->role === 'client'
            ? $request->user()
            : User::where('assigned_domain', $domain)->where('role', 'client')->first();

        // Get saved credentials for one-click employee copy or export
        $savedCredentials = MailboxCredential::where('domain', $domain)->get()->keyBy('username');

        $totalUsageMb = 0;
        $totalQuotaMb = 0;
        foreach ($emails as $em) {
            $totalUsageMb += (float) ($em['usage'] ?? 0);
            $totalQuotaMb += (int) ($em['quota'] ?? 0);
        }

        return view('emails.index', [
            'activeDomain' => $domain,
            'emails' => $emails,
            'clientUser' => $clientUser,
            'savedCredentials' => $savedCredentials,
            'mailboxesCount' => count($emails),
            'totalUsageMb' => round($totalUsageMb, 2),
            'totalQuotaMb' => $totalQuotaMb,
            'apiError' => $error,
            'rateLimitStatus' => $api->getRateLimitStatus(),
            'webmailUrl' => 'https://mail.albrolosy.com/',
        ]);
    }

    /**
     * Store a newly created email account with quota checking and credential saving.
     */
    public function store(CreateEmailAccountRequest $request, MxRouteApiService $api): RedirectResponse
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
        $validated = $request->validated();

        $clientUser = $request->user()->role === 'client'
            ? $request->user()
            : User::where('assigned_domain', $domain)->where('role', 'client')->first();

        try {
            // Check limits if enforced for this client
            if ($clientUser) {
                $currentAccounts = null;

                if ($clientUser->max_emails) {
                    $currentAccounts = $api->listEmailAccounts($domain);
                    if (count($currentAccounts) >= $clientUser->max_emails) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', "عذراً، لقد بلغت الحد الأقصى لحسابات البريد المسموح بها في باقتك ({$clientUser->max_emails} حساب). يرجى التواصل مع الإدارة لزيادة الحسابات.");
                    }
                }

                if ($clientUser->storage_limit_mb && ! empty($validated['quota'])) {
                    $currentAccounts = $currentAccounts ?? $api->listEmailAccounts($domain);
                    $currentQuotaTotal = array_sum(array_column($currentAccounts, 'quota'));
                    if (($currentQuotaTotal + (int) $validated['quota']) > $clientUser->storage_limit_mb) {
                        return redirect()->back()
                            ->withInput()
                            ->with('error', "عذراً، المساحة المطلوبة للحساب تتجاوز إجمالي الحصة التخزينية المخصصة لنطاقك ({$clientUser->storage_limit_mb} MB).");
                    }
                }
            }

            $api->createEmailAccount(
                domain: $domain,
                username: $validated['username'],
                password: $validated['password'],
                quota: (int) ($validated['quota'] ?? 1024),
                limit: (int) ($validated['limit'] ?? 9600)
            );

            // Securely store encrypted password for PDF exports and quick share
            MailboxCredential::storeOrUpdate(
                domain: $domain,
                username: $validated['username'],
                password: $validated['password'],
                quota: (int) ($validated['quota'] ?? 1024),
                limit: (int) ($validated['limit'] ?? 9600)
            );

            $credentialsPayload = [
                'email' => strtolower(trim($validated['username'])).'@'.$domain,
                'username' => strtolower(trim($validated['username'])),
                'password' => $validated['password'],
                'quota' => $validated['quota'] ?? 1024,
                'domain' => $domain,
                'webmail_url' => 'https://mail.albrolosy.com/',
            ];

            ActivityLogger::logEmailCreated(
                user: $request->user(),
                username: $validated['username'],
                domain: $domain,
                quota: (int) ($validated['quota'] ?? 1024),
                limit: (int) ($validated['limit'] ?? 9600),
                request: $request
            );

            return redirect()->route('emails.index')
                ->with('status', 'email-created')
                ->with('success', "تم إنشاء صندوق البريد {$validated['username']}@{$domain} بنجاح.")
                ->with('new_mailbox_created', $credentialsPayload);

        } catch (MxRouteRateLimitException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());

        } catch (MxRouteValidationException $e) {
            $field = $e->getField() ?: 'username';
            throw ValidationException::withMessages([
                $field => [$e->getMessage()],
            ]);

        } catch (MxRouteApiException $e) {
            if ($e->getErrorCode() === 'CONFLICT') {
                throw ValidationException::withMessages([
                    'username' => ['اسم المستخدم هذا محجوز بالفعل على دومين '.$domain.'.'],
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified email account.
     */
    public function update(UpdateEmailAccountRequest $request, string $user, MxRouteApiService $api): RedirectResponse
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
        $validated = $request->validated();

        try {
            $api->updateEmailAccount(
                domain: $domain,
                user: $user,
                password: ! empty($validated['password']) ? $validated['password'] : null,
                quota: isset($validated['quota']) ? (int) $validated['quota'] : null,
                limit: isset($validated['limit']) ? (int) $validated['limit'] : null
            );

            if (! empty($validated['password'])) {
                MailboxCredential::storeOrUpdate(
                    domain: $domain,
                    username: $user,
                    password: $validated['password'],
                    quota: isset($validated['quota']) ? (int) $validated['quota'] : null,
                    limit: isset($validated['limit']) ? (int) $validated['limit'] : null
                );
            }

            ActivityLogger::logEmailUpdated(
                user: $request->user(),
                username: $user,
                domain: $domain,
                quota: isset($validated['quota']) ? (int) $validated['quota'] : null,
                limit: isset($validated['limit']) ? (int) $validated['limit'] : null,
                passwordChanged: ! empty($validated['password']),
                request: $request
            );

            return redirect()->route('emails.index')
                ->with('status', 'email-updated')
                ->with('success', "تم تحديث إعدادات البريد {$user}@{$domain} بنجاح.");

        } catch (MxRouteRateLimitException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());

        } catch (MxRouteValidationException $e) {
            $field = $e->getField() ?: 'password';
            throw ValidationException::withMessages([
                $field => [$e->getMessage()],
            ]);

        } catch (MxRouteApiException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified email account.
     */
    public function destroy(Request $request, string $user, MxRouteApiService $api): RedirectResponse
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();

        try {
            $api->deleteEmailAccount($domain, $user);

            // Clean up credential record
            MailboxCredential::where('domain', $domain)->where('username', $user)->delete();

            ActivityLogger::logEmailDeleted(
                user: $request->user(),
                username: $user,
                domain: $domain,
                request: $request
            );

            return redirect()->route('emails.index')
                ->with('status', 'email-deleted')
                ->with('success', "تم حذف صندوق البريد {$user}@{$domain} نهائياً.");

        } catch (MxRouteRateLimitException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());

        } catch (MxRouteApiException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Export all email accounts for the domain to a PDF summary.
     */
    public function exportAllPdf(Request $request, MxRouteApiService $api): Response|BinaryFileResponse
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
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

        $clientUser = $request->user()->role === 'client'
            ? $request->user()
            : User::where('assigned_domain', $domain)->where('role', 'client')->first();

        $data = [
            'domain' => $domain,
            'clientUser' => $clientUser,
            'emails' => $emails,
            'mailboxesCount' => count($emails),
            'totalUsageMb' => round($totalUsageMb, 2),
            'totalQuotaMb' => $totalQuotaMb,
            'serverHost' => 'mail.albrolosy.com',
            'webmailUrl' => 'https://mail.albrolosy.com/',
            'generatedAtFormatted' => now()->translatedFormat('d F Y'),
        ];

        $asDownload = $request->query('view') !== '1' && $request->query('format') !== 'html';

        ActivityLogger::logPdfExport(
            user: $request->user(),
            type: 'all',
            target: "جميع حسابات نطاق {$domain}",
            domain: $domain,
            request: $request
        );

        return DocumentPdfService::render('emails.pdf.accounts-report', $data, "email-accounts-{$domain}.pdf", $asDownload);
    }

    /**
     * Export a single email account card with credentials (username & password) to PDF.
     */
    public function exportSinglePdf(Request $request, string $user, MxRouteApiService $api): Response|BinaryFileResponse
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
        $user = strtolower(trim($user));

        $credential = MailboxCredential::where('domain', $domain)->where('username', $user)->first();

        // Check if password was submitted with request
        if (! empty($request->input('password'))) {
            MailboxCredential::storeOrUpdate($domain, $user, $request->input('password'));
            $plainPassword = $request->input('password');
        } else {
            $plainPassword = $credential?->plain_password ?: null;
        }

        // Ensure a clear password is provided
        if (empty($plainPassword)) {
            $plainPassword = 'Info@2026Albrolosy';
        }

        $emailAddress = $user.'@'.$domain;

        $clientUser = $request->user()->role === 'client'
            ? $request->user()
            : User::where('assigned_domain', $domain)->where('role', 'client')->first();

        $data = [
            'username' => $user,
            'domain' => $domain,
            'emailAddress' => $emailAddress,
            'plainPassword' => $plainPassword,
            'quota' => $credential?->quota ?? 2048,
            'limit' => $credential?->limit ?? 9600,
            'clientUser' => $clientUser,
            'serverHost' => 'mail.albrolosy.com',
            'webmailUrl' => 'https://mail.albrolosy.com/',
            'generatedAtFormatted' => now()->translatedFormat('d F Y'),
        ];

        $asDownload = $request->query('view') !== '1' && $request->query('format') !== 'html';

        ActivityLogger::logPdfExport(
            user: $request->user(),
            type: 'single',
            target: $emailAddress,
            domain: $domain,
            request: $request
        );

        return DocumentPdfService::render('emails.pdf.account-card', $data, "account-card-{$user}@{$domain}.pdf", $asDownload);
    }

    /**
     * Record a log when a user shares an email account PDF.
     */
    public function logShare(Request $request, string $user): JsonResponse
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
        $user = strtolower(trim($user));
        $emailAddress = $user.'@'.$domain;

        $token = MailboxCredential::generateShareToken($domain, $user);
        $publicUrl = route('emails.public-card', [
            'domain' => $domain,
            'user' => $user,
            'token' => $token,
            'view' => 1,
        ]);

        ActivityLogger::logPdfShare(
            user: $request->user(),
            email: $emailAddress,
            domain: $domain,
            request: $request
        );

        return response()->json([
            'success' => true,
            'email' => $emailAddress,
            'token' => $token,
            'public_url' => $publicUrl,
        ]);
    }

    /**
     * Display the comprehensive user guide on email setup & device connection.
     */
    public function guide(Request $request): View
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();

        return view('emails.guide', [
            'activeDomain' => $domain,
            'webmailUrl' => 'https://mail.albrolosy.com/',
            'serverHost' => 'mail.albrolosy.com',
        ]);
    }

    /**
     * Public direct download or view of employee account card PDF (No dashboard login required).
     * Protected by secure HMAC token so employees can view/download without any dashboard interaction.
     */
    public function publicCardPdf(Request $request, string $domain, string $user): Response|BinaryFileResponse
    {
        $domain = strtolower(trim($domain));
        $user = strtolower(trim($user));
        $token = (string) $request->query('token');

        // Security check: Must have valid HMAC share token OR be authenticated
        if (! auth()->check() && ! MailboxCredential::isValidShareToken($domain, $user, $token)) {
            abort(403, 'رابط بطاقة الحساب غير مصرح به أو منتهي الصلاحية.');
        }

        $credential = MailboxCredential::where('domain', $domain)->where('username', $user)->first();

        // Check if password was submitted with request or saved previously
        if (! empty($request->input('password'))) {
            $plainPassword = $request->input('password');
        } else {
            $plainPassword = $credential?->plain_password ?: 'Info@2026Albrolosy';
        }

        $emailAddress = $user.'@'.$domain;

        $clientUser = User::where('assigned_domain', $domain)->where('role', 'client')->first();

        $data = [
            'username' => $user,
            'domain' => $domain,
            'emailAddress' => $emailAddress,
            'plainPassword' => $plainPassword,
            'quota' => $credential?->quota ?? 2048,
            'limit' => $credential?->limit ?? 9600,
            'clientUser' => $clientUser,
            'serverHost' => 'mail.albrolosy.com',
            'webmailUrl' => 'https://mail.albrolosy.com/',
            'generatedAtFormatted' => now()->translatedFormat('d F Y'),
        ];

        $asDownload = $request->query('view') !== '1' && $request->query('format') !== 'html';

        ActivityLogger::log(
            actionType: 'pdf_export',
            actionTitle: "استعراض/تحميل بطاقة اعتماد الحساب (PDF) للبريد [{$emailAddress}]",
            details: [
                'email' => $emailAddress,
                'domain' => $domain,
                'type' => 'employee_direct_card',
            ],
            user: auth()->user(),
            request: $request
        );

        return DocumentPdfService::render('emails.pdf.account-card', $data, "account-card-{$user}@{$domain}.pdf", $asDownload);
    }
}
