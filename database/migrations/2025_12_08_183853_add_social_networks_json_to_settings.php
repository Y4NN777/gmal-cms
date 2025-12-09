<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing social network settings to JSON format
        $socialNetworks = [
            'facebook' => DB::table('settings')->where('key', 'social_facebook')->value('value'),
            'twitter' => DB::table('settings')->where('key', 'social_twitter')->value('value'),
            'instagram' => DB::table('settings')->where('key', 'social_instagram')->value('value'),
            'linkedin' => DB::table('settings')->where('key', 'social_linkedin')->value('value'),
            'youtube' => DB::table('settings')->where('key', 'social_youtube')->value('value'),
        ];

        // Build JSON array of active social networks
        $socialNetworksData = [];
        foreach ($socialNetworks as $platform => $url) {
            if ($url) {
                $socialNetworksData[] = [
                    'platform' => $platform,
                    'url' => $url,
                    'is_active' => true,
                ];
            }
        }

        // Delete old individual social network settings
        DB::table('settings')->whereIn('key', [
            'social_facebook',
            'social_twitter',
            'social_instagram',
            'social_linkedin',
            'social_youtube',
        ])->delete();

        // Insert new JSON-based social networks setting
        DB::table('settings')->insert([
            'key' => 'social_networks',
            'value' => json_encode($socialNetworksData),
            'type' => 'json',
            'group' => 'social',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get current social networks JSON
        $socialNetworksJson = DB::table('settings')->where('key', 'social_networks')->value('value');
        $socialNetworks = json_decode($socialNetworksJson, true) ?? [];

        // Restore individual settings
        $mapping = [
            'facebook' => 'social_facebook',
            'twitter' => 'social_twitter',
            'instagram' => 'social_instagram',
            'linkedin' => 'social_linkedin',
            'youtube' => 'social_youtube',
        ];

        foreach ($mapping as $platform => $key) {
            $network = collect($socialNetworks)->firstWhere('platform', $platform);
            DB::table('settings')->insert([
                'key' => $key,
                'value' => $network['url'] ?? '',
                'type' => 'url',
                'group' => 'social',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Delete JSON setting
        DB::table('settings')->where('key', 'social_networks')->delete();
    }
};
