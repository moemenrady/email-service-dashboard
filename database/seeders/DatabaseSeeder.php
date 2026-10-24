<?php

namespace Database\Seeders;

use App\Models\MailboxCredential;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user - Moemen Team Management
        User::updateOrCreate(
            ['email' => 'admin@moemen.team'],
            [
                'name' => 'Moemen Admin',
                'password' => Hash::make('Password123!'),
                'role' => 'admin',
                'assigned_domain' => 'albrolosy.com',
            ]
        );

        // Client user - assigned specifically to albrolosy.com with email and storage quota
        User::updateOrCreate(
            ['email' => 'albrolosymanagement@albrolosy.com'],
            [
                'name' => 'شركة البرلسي',
                'password' => Hash::make('Xzd\7Xy5ZqU6\;{Clrr3<&'),
                'role' => 'client',
                'assigned_domain' => 'albrolosy.com',
                'max_emails' => 200,
                'storage_limit_mb' => 512000,
            ]
        );

        // Pre-seed credentials for active mailboxes on albrolosy.com so passwords show in exports
        MailboxCredential::storeOrUpdate('albrolosy.com', 'info', 'Info@2026Albrolosy', 2048, 9600);
        MailboxCredential::storeOrUpdate('albrolosy.com', 'test', 'Test@2026Albrolosy', 1024, 9600);
        MailboxCredential::storeOrUpdate('albrolosy.com', 'contact', 'Contact@2026Albrolosy', 1024, 9600);
    }
}
