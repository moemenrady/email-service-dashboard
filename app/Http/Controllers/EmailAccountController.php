<?php

namespace App\Http\Controllers;

use App\Exceptions\MxRouteApiException;
use App\Exceptions\MxRouteRateLimitException;
use App\Exceptions\MxRouteValidationException;
use App\Http\Requests\CreateEmailAccountRequest;
use App\Http\Requests\UpdateEmailAccountRequest;
use App\Models\MailboxCredential;
use App\Models\User;
use App\Services\MxRouteApiService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
    public function exportAllPdf(Request $request, MxRouteApiService $api): Response
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

        $pdf = Pdf::loadView('emails.pdf.accounts-report', [
            'domain' => $domain,
            'clientUser' => $clientUser,
            'emails' => $emails,
            'mailboxesCount' => count($emails),
            'totalUsageMb' => round($totalUsageMb, 2),
            'totalQuotaMb' => $totalQuotaMb,
            'serverHost' => 'mail.albrolosy.com',
            'webmailUrl' => 'https://mail.albrolosy.com/',
            'generatedAt' => now()->format('Y-m-d H:i:s'),
        ]);

        return $pdf->download("email-accounts-{$domain}.pdf");
    }

    /**
     * Export a single email account card with credentials (username & password) to PDF.
     */
    public function exportSinglePdf(Request $request, string $user, MxRouteApiService $api): Response
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
        $user = strtolower(trim($user));

        $credential = MailboxCredential::where('domain', $domain)->where('username', $user)->first();
        $plainPassword = $request->input('password') ?: ($credential?->plain_password ?: '********');

        $emailAddress = $user.'@'.$domain;

        $pdf = Pdf::loadView('emails.pdf.account-card', [
            'username' => $user,
            'domain' => $domain,
            'emailAddress' => $emailAddress,
            'password' => $plainPassword,
            'serverHost' => 'mail.albrolosy.com',
            'webmailUrl' => 'https://mail.albrolosy.com/',
            'generatedAt' => now()->format('Y-m-d H:i'),
        ]);

        return $pdf->download("account-card-{$user}@{$domain}.pdf");
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
}
