<?php

namespace App\Http\Controllers;

use App\Exceptions\MxRouteApiException;
use App\Services\MxRouteApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    /**
     * Switch current active domain.
     */
    public function switchDomain(Request $request): RedirectResponse
    {
        $domain = $request->input('domain');

        if (!$request->user()->canManageDomain($domain)) {
            abort(403, 'Unauthorized to access this domain.');
        }

        session(['active_domain' => $domain]);

        return redirect()->back()->with('success', "Active domain switched to {$domain}.");
    }

    /**
     * Show domain DNS records and status (White-labeled).
     */
    public function showDns(Request $request, MxRouteApiService $api): View
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();
        $dnsInfo = [];
        $error = null;

        try {
            $dnsInfo = $api->getDnsInfo($domain);
        } catch (MxRouteApiException $e) {
            $error = $e->getMessage();
        }

        return view('domains.dns', [
            'activeDomain' => $domain,
            'dnsInfo' => $dnsInfo,
            'apiError' => $error,
        ]);
    }
}
