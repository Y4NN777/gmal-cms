<?php

namespace Database\Seeders;

use App\Models\ImpactMetric;
use Illuminate\Database\Seeder;

class ImpactMetricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metrics = [
            [
                'key' => 'families_helped',
                'label_en' => 'Families Helped',
                'label_fr' => 'Familles Aidées',
                'value' => '200+',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'key' => 'community_events',
                'label_en' => 'Community Events',
                'label_fr' => 'Événements Communautaires',
                'value' => '25+',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'key' => 'funds_raised',
                'label_en' => 'Funds Raised',
                'label_fr' => 'Fonds Collectés',
                'value' => '$75K+',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'key' => 'volunteers',
                'label_en' => 'Volunteers',
                'label_fr' => 'Bénévoles',
                'value' => '150+',
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
    }
}
