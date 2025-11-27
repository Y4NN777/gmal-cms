<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo user first
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'admin@givemealift.org'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Create categories
        $categories = [
            ['name' => 'Community Outreach', 'slug' => 'community-outreach', 'description' => 'Programs focused on community engagement', 'color' => '#EE9446', 'icon' => 'users'],
            ['name' => 'Education', 'slug' => 'education', 'description' => 'Educational initiatives and programs', 'color' => '#219D80', 'icon' => 'book'],
            ['name' => 'Health & Wellness', 'slug' => 'health-wellness', 'description' => 'Health and wellness programs', 'color' => '#3B82F6', 'icon' => 'heart'],
            ['name' => 'Fundraising', 'slug' => 'fundraising', 'description' => 'Fundraising events and campaigns', 'color' => '#EF4444', 'icon' => 'star'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Create 10 published, upcoming, featured events
        \App\Models\Event::factory()
            ->count(10)
            ->published()
            ->featured()
            ->create([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

        // Create additional regular events
        \App\Models\Event::factory()
            ->count(15)
            ->published()
            ->create([
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

        // Create approved and featured testimonials
        \App\Models\Testimonial::factory()
            ->count(6)
            ->approved()
            ->featured()
            ->create();

        // Create additional testimonials
        \App\Models\Testimonial::factory()
            ->count(10)
            ->approved()
            ->create();

        $this->command->info('Demo data seeded successfully!');
    }
}
