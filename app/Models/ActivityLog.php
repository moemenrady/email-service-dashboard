<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'assigned_domain',
        'action_type',
        'action_title',
        'details',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'details' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * User who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for filtering by user.
     */
    public function scopeForUser(Builder $query, ?int $userId): Builder
    {
        if (! $userId) {
            return $query;
        }

        return $query->where('user_id', $userId);
    }

    /**
     * Scope for filtering by action type.
     */
    public function scopeActionType(Builder $query, ?string $type): Builder
    {
        if (! $type || $type === 'all') {
            return $query;
        }

        return $query->where('action_type', $type);
    }

    /**
     * Scope for filtering by domain.
     */
    public function scopeForDomain(Builder $query, ?string $domain): Builder
    {
        if (! $domain || $domain === 'all') {
            return $query;
        }

        return $query->where('assigned_domain', $domain);
    }

    /**
     * Scope for keyword search across action_title, user_name, user_email, ip_address, browser.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $q) use ($term) {
            $q->where('action_title', 'like', "%{$term}%")
                ->orWhere('user_name', 'like', "%{$term}%")
                ->orWhere('user_email', 'like', "%{$term}%")
                ->orWhere('assigned_domain', 'like', "%{$term}%")
                ->orWhere('ip_address', 'like', "%{$term}%")
                ->orWhere('browser', 'like', "%{$term}%")
                ->orWhere('platform', 'like', "%{$term}%");
        });
    }

    /**
     * Scope for filtering by date range.
     */
    public function scopeDateRange(Builder $query, ?string $from, ?string $to): Builder
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        return $query;
    }

    /**
     * Get Arabic label for action type.
     */
    public function getActionTypeArabicAttribute(): string
    {
        return match ($this->action_type) {
            'login' => 'تسجيل دخول',
            'logout' => 'تسجيل خروج',
            'page_view' => 'استعراض صفحة',
            'email_create' => 'إنشاء بريد',
            'email_update' => 'تعديل بريد',
            'email_delete' => 'حذف بريد',
            'forwarder_create' => 'إنشاء تحويل',
            'forwarder_delete' => 'حذف تحويل',
            'pdf_export' => 'تصدير PDF',
            'pdf_share' => 'مشاركة PDF',
            'password_change' => 'تغيير كلمة المرور',
            'profile_update' => 'تعديل الملف',
            'domain_switch' => 'تبديل النطاق',
            default => $this->action_type,
        };
    }

    /**
     * Get badge styling classes for action type.
     */
    public function getActionBadgeClassAttribute(): string
    {
        return match ($this->action_type) {
            'login' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
            'logout' => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
            'page_view' => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
            'email_create' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            'email_update' => 'bg-teal-500/10 text-teal-400 border-teal-500/20',
            'email_delete' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
            'forwarder_create' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
            'forwarder_delete' => 'bg-pink-500/10 text-pink-400 border-pink-500/20',
            'pdf_export' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            'pdf_share' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
            'password_change' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
            'profile_update' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
            'domain_switch' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            default => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
        };
    }

    /**
     * Get device icon emoji.
     */
    public function getDeviceIconAttribute(): string
    {
        return match (strtolower((string) $this->device_type)) {
            'mobile' => '📱',
            'tablet' => '📟',
            'desktop' => '💻',
            default => '🖥️',
        };
    }
}
