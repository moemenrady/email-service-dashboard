<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackClientActivity
{
    /**
     * Map known routes to clear Arabic page titles.
     *
     * @var array<string, string>
     */
    protected array $routeTitles = [
        'emails.index' => 'إدارة حسابات البريد الإلكتروني',
        'forwarders.index' => 'إدارة إعادة التوجيه (Forwarders)',
        'emails.guide' => 'دليل الاستخدام وربط الأجهزة',
        'domains.dns' => 'استعراض إعدادات وسجلات DNS',
        'profile.edit' => 'إعدادات الملف الشخصي',
        'admin.dashboard' => 'لوحة الإدارة الرئيسية',
        'admin.activities.index' => 'سجل النشاطات والتحليلات',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET requests from authenticated users
        if ($request->isMethod('GET') && ! $request->ajax() && $request->user() && $response->isSuccessful()) {
            $routeName = $request->route()?->getName();
            $path = $request->path();

            $pageTitle = $this->routeTitles[$routeName] ?? null;

            if (! $pageTitle) {
                if (str_starts_with($path, 'emails')) {
                    $pageTitle = 'حسابات البريد الإلكتروني';
                } elseif (str_starts_with($path, 'forwarders')) {
                    $pageTitle = 'إعادة التوجيه';
                } elseif (str_starts_with($path, 'guide')) {
                    $pageTitle = 'دليل الاستخدام';
                } elseif (str_starts_with($path, 'domain/dns')) {
                    $pageTitle = 'سجلات DNS';
                } elseif (str_starts_with($path, 'admin/activities')) {
                    $pageTitle = 'سجل النشاطات والتحليلات';
                } elseif (str_starts_with($path, 'admin')) {
                    $pageTitle = 'لوحة الإدارة';
                }
            }

            if ($pageTitle) {
                // Throttle identical page logs per user to once every 2 minutes
                $cacheKey = 'act_pv_'.$request->user()->id.'_'.md5($routeName ?: $path);
                if (! Cache::has($cacheKey)) {
                    Cache::put($cacheKey, true, now()->addMinutes(2));

                    ActivityLogger::logPageView(
                        user: $request->user(),
                        pageTitle: $pageTitle,
                        url: $request->fullUrl(),
                        request: $request
                    );
                }
            }
        }

        return $response;
    }
}
