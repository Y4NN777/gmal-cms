<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@givemealift.org'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@givemealift.org',
                'password' => Hash::make('password'), // Change in production!
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Editor User
        User::updateOrCreate(
            ['email' => 'editor@givemealift.org'],
            [
                'name' => 'Content Editor',
                'email' => 'editor@givemealift.org',
                'password' => Hash::make('password'), // Change in production!
                'role' => 'editor',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Viewer User
        User::updateOrCreate(
            ['email' => 'viewer@givemealift.org'],
            [
                'name' => 'Content Viewer',
                'email' => 'viewer@givemealift.org',
                'password' => Hash::make('password'), // Change in production!
                'role' => 'viewer',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin users created successfully!');
        $this->command->info('📧 Admin: admin@givemealift.org / password');
        $this->command->info('📧 Editor: editor@givemealift.org / password');
        $this->command->info('📧 Viewer: viewer@givemealift.org / password');
    }
}
