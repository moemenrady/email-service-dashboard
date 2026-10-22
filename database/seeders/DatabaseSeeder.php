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
        // Admin user - Moemen Team
        User::firstOrCreate(
            ['email' => 'admin@moemen.team'],
            [
                'name' => 'Moemen Admin',
                'password' => Hash::make('Password123!'),
                'role' => 'admin',
                'assigned_domain' => 'moemen-team.com',
            ]
        );

        // Client user
        User::firstOrCreate(
            ['email' => 'client@moemen.team'],
            [
                'name' => 'Client Demo',
                'password' => Hash::make('Password123!'),
                'role' => 'client',
                'assigned_domain' => 'moemen-team.com',
            ]
        );
    }
}
