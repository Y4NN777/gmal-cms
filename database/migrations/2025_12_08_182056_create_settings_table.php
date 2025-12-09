<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, url, email
            $table->string('group')->default('general'); // general, social, contact, appearance
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            // General
            ['key' => 'site_name', 'value' => 'Give Me A Lift', 'type' => 'text', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_description', 'value' => 'Empowering communities through education and support', 'type' => 'textarea', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_logo', 'value' => '/images/logo.png', 'type' => 'image', 'group' => 'appearance', 'created_at' => now(), 'updated_at' => now()],
            
            // Contact
            ['key' => 'contact_email', 'value' => 'contact@givemealift.org', 'type' => 'email', 'group' => 'contact', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_phone', 'value' => '+1 (234) 567-8900', 'type' => 'text', 'group' => 'contact', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_address', 'value' => '123 Charity Lane, Oklahoma City, OK 73102', 'type' => 'textarea', 'group' => 'contact', 'created_at' => now(), 'updated_at' => now()],
            
            // Social Networks
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/givemealift', 'type' => 'url', 'group' => 'social', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/givemealift', 'type' => 'url', 'group' => 'social', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/givemealift', 'type' => 'url', 'group' => 'social', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/givemealift', 'type' => 'url', 'group' => 'social', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@givemealift', 'type' => 'url', 'group' => 'social', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
