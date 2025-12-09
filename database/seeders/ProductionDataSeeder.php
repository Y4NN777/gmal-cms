<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\ImpactMetric;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductionDataSeeder extends Seeder
{
    /**
     * Seed production data with real values.
     */
    public function run(): void
    {
        // Get or create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@givemealift.org'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Seed Categories (Real data)
        $categories = [
            [
                'name' => 'Community Outreach',
                'slug' => 'community-outreach',
                'description' => 'Programs focused on community engagement',
                'color' => '#EE9446',
                'icon' => 'Users',
                'name_en' => 'Community Outreach',
                'name_fr' => 'Sensibilisation Communautaire',
                'description_en' => 'Programs focused on community engagement',
                'description_fr' => 'Programmes axés sur l\'engagement communautaire',
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Educational initiatives and programs',
                'color' => '#219D80',
                'icon' => 'book',
                'name_en' => 'Education',
                'name_fr' => 'Éducation',
                'description_en' => 'Educational initiatives and programs',
                'description_fr' => 'Initiatives et programmes éducatifs',
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'description' => 'Health and wellness programs',
                'color' => '#3B82F6',
                'icon' => 'Heart',
                'name_en' => 'Health & Wellness',
                'name_fr' => 'Santé et Bien-être',
                'description_en' => 'Health and wellness programs',
                'description_fr' => 'Programmes de santé et bien-être',
            ],
            [
                'name' => 'Fundraising',
                'slug' => 'fundraising',
                'description' => 'Fundraising events and campaigns',
                'color' => '#EF4444',
                'icon' => 'Globe',
                'name_en' => 'Fundraising',
                'name_fr' => 'Collecte de Fonds',
                'description_en' => 'Fundraising events and campaigns',
                'description_fr' => 'Événements et campagnes de collecte de fonds',
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Seed Impact Metrics (Real current values)
        $metrics = [
            [
                'key' => 'families_helped',
                'label_en' => 'Families Helped',
                'label_fr' => 'Familles Aidées',
                'value' => '20+',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'community_events',
                'label_en' => 'Community Events',
                'label_fr' => 'Événements Communautaires',
                'value' => '10+',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'funds_raised',
                'label_en' => 'Funds Raised',
                'label_fr' => 'Fonds Collectés',
                'value' => '$5K+',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'key' => 'volunteers',
                'label_en' => 'Volunteers',
                'label_fr' => 'Bénévoles',
                'value' => '30+',
                'order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($metrics as $metric) {
            ImpactMetric::updateOrCreate(
                ['key' => $metric['key']],
                $metric
            );
        }

        // Seed Settings (Real values)
        $settings = [
            ['key' => 'site_name', 'value' => 'Give Me A Lift'],
            ['key' => 'site_description', 'value' => 'Empowering communities through education and support'],
            ['key' => 'contact_email', 'value' => 'contactus@givemealift.org'],
            ['key' => 'contact_phone', 'value' => "+1 405 589 0915\n+226 75 43 45 45\n+226 67 18 24 38"],
            ['key' => 'contact_address', 'value' => "United States\nBurkina Faso"],
            [
                'key' => 'social_networks',
                'value' => json_encode([
                    ['platform' => 'facebook', 'url' => 'https://facebook.com/givemealift', 'is_active' => true],
                    ['platform' => 'twitter', 'url' => 'https://twitter.com/givemealift', 'is_active' => true],
                    ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/givemealift', 'is_active' => true],
                ])
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Seed Sample Past Events (Realistic data based on your mission)
        $communityOutreach = Category::where('slug', 'community-outreach')->first();
        $education = Category::where('slug', 'education')->first();
        $healthWellness = Category::where('slug', 'health-wellness')->first();
        $fundraising = Category::where('slug', 'fundraising')->first();

        $sampleEvents = [
            [
                'title' => 'Back-to-School Supply Drive 2024',
                'slug' => 'back-to-school-supply-drive-2024',
                'description' => 'Annual event providing school supplies to students in Burkina Faso. Collected over 500 backpacks filled with notebooks, pens, and educational materials for children in need.',
                'category_id' => $education->id,
                'location' => 'Ouagadougou, Burkina Faso',
                'start_date' => now()->subMonths(4),
                'event_date' => now()->subMonths(4),
                'end_date' => now()->subMonths(4)->addDays(2),
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'Community Health Fair',
                'slug' => 'community-health-fair-2024',
                'description' => 'Free health screenings, vaccinations, and health education workshops for families. Partnered with local clinics to provide essential healthcare services to underserved communities.',
                'category_id' => $healthWellness->id,
                'location' => 'Bobo-Dioulasso, Burkina Faso',
                'start_date' => now()->subMonths(3),
                'event_date' => now()->subMonths(3),
                'end_date' => now()->subMonths(3)->addDay(),
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'Annual Fundraising Gala',
                'slug' => 'annual-fundraising-gala-2024',
                'description' => 'Elegant evening event bringing together supporters, donors, and community leaders to celebrate our impact and raise funds for educational programs. Featured dinner, live music, and inspiring stories from beneficiaries.',
                'category_id' => $fundraising->id,
                'location' => 'Oklahoma City, OK',
                'start_date' => now()->subMonths(2),
                'event_date' => now()->subMonths(2),
                'end_date' => now()->subMonths(2),
                'status' => 'published',
                'is_featured' => true,
            ],
            [
                'title' => 'Youth Mentorship Program Launch',
                'slug' => 'youth-mentorship-program-launch',
                'description' => 'Kicked off our new mentorship program connecting local students with professional mentors. Focus on career guidance, life skills, and educational support for at-risk youth.',
                'category_id' => $education->id,
                'location' => 'Oklahoma City, OK',
                'start_date' => now()->subMonths(5),
                'event_date' => now()->subMonths(5),
                'end_date' => now()->subMonths(5),
                'status' => 'published',
                'is_featured' => false,
            ],
            [
                'title' => 'Clean Water Initiative Workshop',
                'slug' => 'clean-water-initiative-workshop',
                'description' => 'Educational workshop on water sanitation and hygiene. Taught families about water purification techniques and distributed water filters to 50 households.',
                'category_id' => $communityOutreach->id,
                'location' => 'Koudougou, Burkina Faso',
                'start_date' => now()->subMonths(6),
                'event_date' => now()->subMonths(6),
                'end_date' => now()->subMonths(6)->addDays(3),
                'status' => 'published',
                'is_featured' => false,
            ],
        ];

        foreach ($sampleEvents as $eventData) {
            Event::updateOrCreate(
                ['slug' => $eventData['slug']],
                array_merge($eventData, [
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ])
            );
        }

        // Seed Sample Testimonials (Realistic and inspiring)
        $sampleTestimonials = [
            [
                'name' => 'Marie Ouédraogo',
                'position' => 'Parent',
                'organization' => null,
                'content' => 'Give Me A Lift provided school supplies for my three children. Now they can attend school with everything they need. This organization truly cares about our children\'s future.',
                'rating' => 5,
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'name' => 'John Smith',
                'position' => 'Volunteer Coordinator',
                'organization' => null,
                'content' => 'Volunteering with Give Me A Lift has been one of the most rewarding experiences of my life. Seeing the direct impact we make in these communities is incredible.',
                'rating' => 5,
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'name' => 'Aminata Sankara',
                'position' => 'Community Leader',
                'organization' => 'Koudougou Village Council',
                'content' => 'The health fair organized by Give Me A Lift brought essential healthcare services to our village. Many families received medical attention they couldn\'t otherwise afford.',
                'rating' => 5,
                'status' => 'approved',
                'is_featured' => true,
            ],
            [
                'name' => 'David Johnson',
                'position' => 'Monthly Donor',
                'organization' => null,
                'content' => 'I\'ve been supporting Give Me A Lift for two years now. Their transparency and dedication to making a real difference is why I continue to donate every month.',
                'rating' => 5,
                'status' => 'approved',
                'is_featured' => false,
            ],
            [
                'name' => 'Fatou Traoré',
                'position' => 'Student',
                'organization' => 'Lycée de Ouagadougou',
                'content' => 'Thanks to the mentorship program, I now have guidance for my future career. My mentor has opened my eyes to possibilities I never knew existed.',
                'rating' => 5,
                'status' => 'approved',
                'is_featured' => false,
            ],
        ];

        foreach ($sampleTestimonials as $testimonialData) {
            Testimonial::updateOrCreate(
                ['name' => $testimonialData['name']],
                $testimonialData
            );
        }

        $this->command->info('✅ Production data seeded successfully!');
        $this->command->info('   - Categories: 4');
        $this->command->info('   - Impact Metrics: 4');
        $this->command->info('   - Settings: ' . count($settings));
        $this->command->info('   - Sample Events: ' . count($sampleEvents));
        $this->command->info('   - Sample Testimonials: ' . count($sampleTestimonials));
    }
}
