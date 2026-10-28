<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivityLogger
{
    /**
     * Parse User-Agent string to detect device type, browser name and version, and operating system.
     *
     * @return array{device_type: string, browser: string, platform: string}
     */
    public static function parseUserAgent(?string $userAgent): array
    {
        if (empty($userAgent)) {
            return [
                'device_type' => 'Desktop',
                'browser' => 'Unknown',
                'platform' => 'Unknown',
            ];
        }

        $deviceType = 'Desktop';
        $platform = 'Unknown';
        $browser = 'Unknown';

        // 1. Detect Platform / OS
        if (preg_match('/iPhone/i', $userAgent)) {
            $platform = 'iOS (iPhone)';
            $deviceType = 'Mobile';
        } elseif (preg_match('/iPad/i', $userAgent)) {
            $platform = 'iOS (iPad)';
            $deviceType = 'Tablet';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $platform = 'Android';
            $deviceType = preg_match('/Mobile/i', $userAgent) ? 'Mobile' : 'Tablet';
        } elseif (preg_match('/Windows NT 10\.0/i', $userAgent)) {
            $platform = 'Windows 10/11';
        } elseif (preg_match('/Windows NT 6\.3/i', $userAgent)) {
            $platform = 'Windows 8.1';
        } elseif (preg_match('/Windows NT 6\.2/i', $userAgent)) {
            $platform = 'Windows 8';
        } elseif (preg_match('/Windows NT 6\.1/i', $userAgent)) {
            $platform = 'Windows 7';
        } elseif (preg_match('/Windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/Ubuntu/i', $userAgent)) {
            $platform = 'Ubuntu Linux';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            $platform = 'Linux';
        } elseif (preg_match('/CrOS/i', $userAgent)) {
            $platform = 'Chrome OS';
        }

        // 2. Extra Device Type checks if still desktop
        if ($deviceType === 'Desktop') {
            if (preg_match('/tablet|ipad|playbook|silk/i', $userAgent)) {
                $deviceType = 'Tablet';
            } elseif (preg_match('/mobile|phone|blackberry|opera mini|iemobile/i', $userAgent)) {
                $deviceType = 'Mobile';
            }
        }

        // 3. Detect Browser Name & Version
        if (preg_match('/Edg(?:e|A|iOS)?\/([0-9\.]+)/i', $userAgent, $matches)) {
            $browser = 'Edge '.explode('.', $matches[1])[0];
        } elseif (preg_match('/SamsungBrowser\/([0-9\.]+)/i', $userAgent, $matches)) {
            $browser = 'Samsung Internet '.explode('.', $matches[1])[0];
        } elseif (preg_match('/OPR\/([0-9\.]+)/i', $userAgent, $matches) || preg_match('/Opera\/([0-9\.]+)/i', $userAgent, $matches)) {
            $browser = 'Opera '.explode('.', $matches[1])[0];
        } elseif (preg_match('/Chrome\/([0-9\.]+)/i', $userAgent, $matches) || preg_match('/CriOS\/([0-9\.]+)/i', $userAgent, $matches)) {
            $browser = 'Chrome '.explode('.', $matches[1])[0];
        } elseif (preg_match('/Firefox\/([0-9\.]+)/i', $userAgent, $matches) || preg_match('/FxiOS\/([0-9\.]+)/i', $userAgent, $matches)) {
            $browser = 'Firefox '.explode('.', $matches[1])[0];
        } elseif (preg_match('/Version\/([0-9\.]+).*Safari/i', $userAgent, $matches)) {
            $browser = 'Safari '.explode('.', $matches[1])[0];
        } elseif (preg_match('/MSIE\s([0-9\.]+)/i', $userAgent, $matches) || preg_match('/Trident\/.*rv:([0-9\.]+)/i', $userAgent, $matches)) {
            $browser = 'Internet Explorer '.explode('.', $matches[1])[0];
        } else {
            $browser = 'Browser';
        }

        return [
            'device_type' => $deviceType,
            'browser' => $browser,
            'platform' => $platform,
        ];
    }

    /**
     * Record a new activity log entry.
     *
     * @param  array<string, mixed>  $details
     */
    public static function log(
        string $actionType,
        string $actionTitle,
        array $details = [],
        ?User $user = null,
        ?Request $request = null
    ): ?ActivityLog {
        try {
            $req = $request ?: (app()->bound('request') ? request() : null);
            $currentUser = $user ?: (Auth::check() ? Auth::user() : null);

            $userAgent = $req?->userAgent();
            $ipAddress = $req?->ip();

            $parsedAgent = static::parseUserAgent($userAgent);

            $domain = null;
            if ($currentUser instanceof User) {
                $domain = $currentUser->getActiveDomain();
            }
            if (! $domain && session()->has('active_domain')) {
                $domain = session('active_domain');
            }
            if (! $domain && isset($details['domain'])) {
                $domain = (string) $details['domain'];
            }

            return ActivityLog::create([
                'user_id' => $currentUser?->id,
                'user_name' => $currentUser?->name,
                'user_email' => $currentUser?->email,
                'user_role' => $currentUser?->role,
                'assigned_domain' => $domain,
                'action_type' => $actionType,
                'action_title' => $actionTitle,
                'details' => $details,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent ? substr($userAgent, 0, 500) : null,
                'device_type' => $parsedAgent['device_type'],
                'browser' => $parsedAgent['browser'],
                'platform' => $parsedAgent['platform'],
            ]);
        } catch (Throwable $e) {
            Log::warning('ActivityLogger failed to record log: '.$e->getMessage(), [
                'action_type' => $actionType,
                'exception' => $e,
            ]);

            return null;
        }
    }

    /**
     * Log successful user login.
     */
    public static function logLogin(User $user, ?Request $request = null): ?ActivityLog
    {
        return static::log(
            actionType: 'login',
            actionTitle: 'تسجيل دخول إلى النظام',
            details: [
                'email' => $user->email,
                'role' => $user->role,
                'domain' => $user->getActiveDomain(),
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log user logout.
     */
    public static function logLogout(User $user, ?Request $request = null): ?ActivityLog
    {
        return static::log(
            actionType: 'logout',
            actionTitle: 'تسجيل خروج من النظام',
            details: [
                'email' => $user->email,
                'role' => $user->role,
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log page view (throttled to avoid log flooding).
     */
    public static function logPageView(User $user, string $pageTitle, string $url, ?Request $request = null): ?ActivityLog
    {
        return static::log(
            actionType: 'page_view',
            actionTitle: "زيارة صفحة: {$pageTitle}",
            details: [
                'page' => $pageTitle,
                'url' => $url,
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log creation of an email account.
     */
    public static function logEmailCreated(User $user, string $username, string $domain, int $quota, int $limit, ?Request $request = null): ?ActivityLog
    {
        $email = strtolower(trim($username)).'@'.$domain;

        return static::log(
            actionType: 'email_create',
            actionTitle: "إنشاء حساب بريد جديد: {$email}",
            details: [
                'email' => $email,
                'username' => $username,
                'domain' => $domain,
                'quota_mb' => $quota,
                'daily_limit' => $limit,
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log update of an email account.
     */
    public static function logEmailUpdated(User $user, string $username, string $domain, ?int $quota, ?int $limit, bool $passwordChanged, ?Request $request = null): ?ActivityLog
    {
        $email = strtolower(trim($username)).'@'.$domain;

        return static::log(
            actionType: 'email_update',
            actionTitle: "تعديل إعدادات البريد: {$email}",
            details: [
                'email' => $email,
                'username' => $username,
                'domain' => $domain,
                'quota_mb' => $quota,
                'daily_limit' => $limit,
                'password_changed' => $passwordChanged,
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log deletion of an email account.
     */
    public static function logEmailDeleted(User $user, string $username, string $domain, ?Request $request = null): ?ActivityLog
    {
        $email = strtolower(trim($username)).'@'.$domain;

        return static::log(
            actionType: 'email_delete',
            actionTitle: "حذف حساب البريد نهائياً: {$email}",
            details: [
                'email' => $email,
                'username' => $username,
                'domain' => $domain,
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log creation of a forwarder.
     *
     * @param  array<int, string>  $destinations
     */
    public static function logForwarderCreated(User $user, string $alias, array $destinations, string $domain, ?Request $request = null): ?ActivityLog
    {
        $source = "{$alias}@{$domain}";
        $destString = implode(', ', $destinations);

        return static::log(
            actionType: 'forwarder_create',
            actionTitle: "إنشاء إعادة توجيه من {$source} إلى [{$destString}]",
            details: [
                'source' => $source,
                'alias' => $alias,
                'domain' => $domain,
                'destinations' => $destinations,
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log deletion of a forwarder.
     */
    public static function logForwarderDeleted(User $user, string $alias, string $domain, ?Request $request = null): ?ActivityLog
    {
        $source = "{$alias}@{$domain}";

        return static::log(
            actionType: 'forwarder_delete',
            actionTitle: "حذف إعادة التوجيه: {$source}",
            details: [
                'source' => $source,
                'alias' => $alias,
                'domain' => $domain,
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log PDF export (single card or all accounts).
     */
    public static function logPdfExport(User $user, string $type, string $target, string $domain, ?Request $request = null): ?ActivityLog
    {
        $title = $type === 'all'
            ? "تصدير كشف شامل لجميع الحسابات (PDF) للنطاق [{$domain}]"
            : "تصدير بطاقة حساب بريدي (PDF) للبريد [{$target}]";

        return static::log(
            actionType: 'pdf_export',
            actionTitle: $title,
            details: [
                'export_type' => $type,
                'target' => $target,
                'domain' => $domain,
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log PDF sharing via Native Share API or Quick Link.
     */
    public static function logPdfShare(User $user, string $email, string $domain, ?Request $request = null): ?ActivityLog
    {
        return static::log(
            actionType: 'pdf_share',
            actionTitle: "مشاركة بطاقة اعتماد الحساب (PDF) للبريد [{$email}]",
            details: [
                'email' => $email,
                'domain' => $domain,
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log user password change.
     */
    public static function logPasswordChanged(User $user, ?Request $request = null): ?ActivityLog
    {
        return static::log(
            actionType: 'password_change',
            actionTitle: 'تغيير كلمة المرور الخاصة بالحساب',
            details: [
                'email' => $user->email,
            ],
            user: $user,
            request: $request
        );
    }

    /**
     * Log user profile updates.
     *
     * @param  array<string, mixed>  $changes
     */
    public static function logProfileUpdated(User $user, array $changes, ?Request $request = null): ?ActivityLog
    {
        return static::log(
            actionType: 'profile_update',
            actionTitle: 'تحديث بيانات الملف الشخصي',
            details: [
                'updated_fields' => array_keys($changes),
            ],
            user: $user,
            request: $request
        );
    }
}
