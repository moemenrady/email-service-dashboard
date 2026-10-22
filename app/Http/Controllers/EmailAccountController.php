<?php

namespace App\Http\Controllers;

use App\Exceptions\MxRouteApiException;
use App\Exceptions\MxRouteRateLimitException;
use App\Exceptions\MxRouteValidationException;
use App\Http\Requests\CreateEmailAccountRequest;
use App\Http\Requests\UpdateEmailAccountRequest;
use App\Services\MxRouteApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('emails.index', [
            'activeDomain' => $domain,
            'emails' => $emails,
            'apiError' => $error,
            'rateLimitStatus' => $api->getRateLimitStatus(),
        ]);
    }

    /**
     * Store a newly created email account.
     */
    public function store(CreateEmailAccountRequest $request, MxRouteApiService $api): RedirectResponse
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
        $validated = $request->validated();

        try {
            $api->createEmailAccount(
                domain: $domain,
                username: $validated['username'],
                password: $validated['password'],
                quota: (int) ($validated['quota'] ?? 1024),
                limit: (int) ($validated['limit'] ?? 9600)
            );

            return redirect()->route('emails.index')
                ->with('status', 'email-created')
                ->with('success', "Mailbox {$validated['username']}@{$domain} has been created successfully.");

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
                    'username' => ['This mailbox username is already taken on ' . $domain . '.'],
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
                password: !empty($validated['password']) ? $validated['password'] : null,
                quota: isset($validated['quota']) ? (int) $validated['quota'] : null,
                limit: isset($validated['limit']) ? (int) $validated['limit'] : null
            );

            return redirect()->route('emails.index')
                ->with('status', 'email-updated')
                ->with('success', "Mailbox {$user}@{$domain} has been updated successfully.");

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

            return redirect()->route('emails.index')
                ->with('status', 'email-deleted')
                ->with('success', "Mailbox {$user}@{$domain} was successfully deleted.");

        } catch (MxRouteRateLimitException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());

        } catch (MxRouteApiException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
