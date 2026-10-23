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

        if (! $request->user()->canManageDomain($domain)) {
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

        // White-label: Guarantee zero leaks of upstream hosting to clients
        if (! $request->user()->isAdmin() && ! empty($dnsInfo)) {
            if (isset($dnsInfo['spf']['value'])) {
                $dnsInfo['spf']['value'] = str_ireplace('include:mxroute.com', 'include:mail.albrolosy.com', $dnsInfo['spf']['value']);
                $dnsInfo['spf']['value'] = preg_replace('/mxroute(\.[a-z]+)?/i', 'mail.albrolosy.com', $dnsInfo['spf']['value']);
            }
            if (isset($dnsInfo['mx_records']) && is_array($dnsInfo['mx_records'])) {
                foreach ($dnsInfo['mx_records'] as &$mx) {
                    if (isset($mx['hostname']) && (stripos($mx['hostname'], 'mxrouting.net') !== false || stripos($mx['hostname'], 'mxroute') !== false)) {
                        $mx['hostname'] = 'mail.albrolosy.com';
                    }
                }
            }
        }

        return view('domains.dns', [
            'activeDomain' => $domain,
            'dnsInfo' => $dnsInfo,
            'apiError' => $error,
            'webmailUrl' => 'https://mail.albrolosy.com/',
            'serverHost' => 'mail.albrolosy.com',
        ]);
    }
}
