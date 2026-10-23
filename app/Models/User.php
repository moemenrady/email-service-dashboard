<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'assigned_domain',
        'max_emails',
        'storage_limit_mb',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'max_emails' => 'integer',
            'storage_limit_mb' => 'integer',
        ];
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Determine if user has permission to manage the given domain.
     */
    public function canManageDomain(?string $domain): bool
    {
        if (empty($domain)) {
            return false;
        }

        if ($this->isAdmin()) {
            return true;
        }

        // If assigned_domain is null, they can manage the system default domain
        $assigned = $this->assigned_domain ?: config('mxroute.default_domain');

        return strcasecmp($assigned, $domain) === 0;
    }

    /**
     * Retrieve the active domain for this user session or account.
     */
    public function getActiveDomain(): string
    {
        return $this->assigned_domain ?: config('mxroute.default_domain', 'moemen-team.com');
    }
}
