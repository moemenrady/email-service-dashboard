<?php

namespace App\Http\Controllers;

use App\Exceptions\MxRouteApiException;
use App\Models\User;
use App\Services\MxRouteApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

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
     * Create a new client user and assign them a domain.
     */
    public function storeClient(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults()],
            'assigned_domain' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'يرجى إدخال اسم العميل.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني للعميل.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل بالفعل.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'assigned_domain.required' => 'يرجى اختيار الدومين المخصص للعميل.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'assigned_domain' => strtolower(trim($validated['assigned_domain'])),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'تم إنشاء حساب العميل وتخصيص الدومين له بنجاح.');
    }

    /**
     * Update client details and assigned domain.
     */
    public function updateClient(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', Password::defaults()],
            'assigned_domain' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'يرجى إدخال اسم العميل.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني للعميل.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل لعميل آخر.',
            'assigned_domain.required' => 'يرجى اختيار الدومين المخصص للعميل.',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'assigned_domain' => strtolower(trim($validated['assigned_domain'])),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'تم تحديث بيانات العميل بنجاح.');
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
        if (!empty($domain)) {
            session(['active_domain' => strtolower(trim($domain))]);
        }

        return redirect()->route('emails.index')->with('success', "تم تحويل لوحة الإدارة إلى الدومين [{$domain}].");
    }
}
