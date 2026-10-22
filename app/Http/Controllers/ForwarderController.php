<?php

namespace App\Http\Controllers;

use App\Exceptions\MxRouteApiException;
use App\Exceptions\MxRouteRateLimitException;
use App\Exceptions\MxRouteValidationException;
use App\Http\Requests\CreateForwarderRequest;
use App\Services\MxRouteApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ForwarderController extends Controller
{
    /**
     * Display a listing of forwarders for the active domain.
     */
    public function index(Request $request, MxRouteApiService $api): View
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
        $forwarders = [];
        $error = null;

        try {
            $forwarders = $api->listForwarders($domain);
        } catch (MxRouteRateLimitException $e) {
            $error = $e->getMessage();
        } catch (MxRouteApiException $e) {
            $error = $e->getMessage();
        }

        return view('forwarders.index', [
            'activeDomain' => $domain,
            'forwarders' => $forwarders,
            'apiError' => $error,
            'rateLimitStatus' => $api->getRateLimitStatus(),
        ]);
    }

    /**
     * Store a newly created forwarder.
     */
    public function store(CreateForwarderRequest $request, MxRouteApiService $api): RedirectResponse
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
        $validated = $request->validated();

        try {
            $api->createForwarder(
                domain: $domain,
                alias: $validated['alias'],
                destinations: $validated['destinations']
            );

            return redirect()->route('forwarders.index')
                ->with('status', 'forwarder-created')
                ->with('success', "Forwarder {$validated['alias']}@{$domain} has been created successfully.");

        } catch (MxRouteRateLimitException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());

        } catch (MxRouteValidationException $e) {
            $field = $e->getField() ?: 'alias';
            throw ValidationException::withMessages([
                $field => [$e->getMessage()],
            ]);

        } catch (MxRouteApiException $e) {
            if ($e->getErrorCode() === 'CONFLICT') {
                throw ValidationException::withMessages([
                    'alias' => ['This forwarder alias is already in use.'],
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified forwarder.
     */
    public function destroy(Request $request, string $alias, MxRouteApiService $api): RedirectResponse
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();

        try {
            $api->deleteForwarder($domain, $alias);

            return redirect()->route('forwarders.index')
                ->with('status', 'forwarder-deleted')
                ->with('success', "Forwarder {$alias}@{$domain} was removed.");

        } catch (MxRouteRateLimitException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());

        } catch (MxRouteApiException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
