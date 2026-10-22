<?php

namespace App\Http\Controllers;

use App\Exceptions\MxRouteApiException;
use App\Exceptions\MxRouteRateLimitException;
use App\Services\MxRouteApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, MxRouteApiService $api): View
    {
        $domain = $request->attributes->get('active_domain') ?? $request->user()->getActiveDomain();

        $emails = [];
        $forwarders = [];
        $quota = [];
        $dnsInfo = [];
        $availableDomains = [];
        $errorMessage = null;

        try {
            // Get available domains if user is admin
            if ($request->user()->isAdmin()) {
                try {
                    $availableDomains = $api->listDomains();
                } catch (\Exception $e) {
                    $availableDomains = [$domain];
                }
            } else {
                $availableDomains = [$domain];
            }

            // Fetch email accounts
            try {
                $emails = $api->listEmailAccounts($domain);
            } catch (MxRouteRateLimitException $e) {
                $errorMessage = $e->getMessage();
            } catch (MxRouteApiException $e) {
                Log::warning("Could not load email accounts for {$domain}: {$e->getMessage()}");
            }

            // Fetch forwarders
            try {
                $forwarders = $api->listForwarders($domain);
            } catch (MxRouteApiException $e) {
                Log::warning("Could not load forwarders for {$domain}: {$e->getMessage()}");
            }

            // Fetch Quota overview
            try {
                $quota = $api->getQuota();
            } catch (MxRouteApiException $e) {
                Log::warning("Could not load quota: {$e->getMessage()}");
            }

            // Fetch DNS info for domain
            try {
                $dnsInfo = $api->getDnsInfo($domain);
            } catch (MxRouteApiException $e) {
                // DNS info optional on dashboard
            }

        } catch (\Exception $e) {
            $errorMessage = MxRouteApiException::sanitizeMessage($e->getMessage());
        }

        $rateLimitStatus = $api->getRateLimitStatus();

        return view('dashboard', [
            'activeDomain' => $domain,
            'availableDomains' => $availableDomains,
            'emails' => $emails,
            'totalEmails' => count($emails),
            'forwarders' => $forwarders,
            'totalForwarders' => count($forwarders),
            'quota' => $quota,
            'dnsInfo' => $dnsInfo,
            'rateLimitStatus' => $rateLimitStatus,
            'errorMessage' => $errorMessage,
        ]);
    }
}
