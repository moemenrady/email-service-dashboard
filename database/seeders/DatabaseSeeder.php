<?php

namespace Database\Seeders;

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
            ['email' => 'client@albrolosy.com'],
            [
                'name' => 'شركة البرلسي',
                'password' => Hash::make('Password123!'),
                'role' => 'client',
                'assigned_domain' => 'albrolosy.com',
                'max_emails' => 10,
                'storage_limit_mb' => 5120,
            ]
        );
    }
}
