<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class MailboxCredential extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'username',
        'email',
        'password_encrypted',
        'quota',
        'limit',
    ];

    /**
     * Store encrypted password.
     */
    public function setPlainPasswordAttribute(?string $value): void
    {
        if (! empty($value)) {
            $this->attributes['password_encrypted'] = Crypt::encryptString($value);
        }
    }

    /**
     * Retrieve decrypted plain password safely.
     */
    public function getPlainPasswordAttribute(): ?string
    {
        if (empty($this->password_encrypted)) {
            return null;
        }

        try {
            return Crypt::decryptString($this->password_encrypted);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Helper to find or set credentials for a mailbox.
     */
    public static function storeOrUpdate(string $domain, string $username, ?string $password, ?int $quota = null, ?int $limit = null): self
    {
        $domain = strtolower(trim($domain));
        $username = strtolower(trim($username));
        $email = $username.'@'.$domain;

        $record = static::firstOrNew([
            'domain' => $domain,
            'username' => $username,
        ]);

        $record->email = $email;
        if (! empty($password)) {
            $record->plain_password = $password;
        }
        if ($quota !== null) {
            $record->quota = $quota;
        }
        if ($limit !== null) {
            $record->limit = $limit;
        }

        $record->save();

        return $record;
    }
}
