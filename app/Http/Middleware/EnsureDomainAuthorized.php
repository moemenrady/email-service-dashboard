<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDomainAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Domain can be from route parameter or query/session or user active domain
        $requestedDomain = $request->route('domain') ?? $request->get('domain') ?? session('active_domain') ?? $user->getActiveDomain();

        if (!$user->canManageDomain($requestedDomain)) {
            abort(403, "You do not have authorization to manage the domain [{$requestedDomain}].");
        }

        // Store active domain in request attributes and session
        $request->attributes->set('active_domain', $requestedDomain);
        session(['active_domain' => $requestedDomain]);

        return $next($request);
    }
}
