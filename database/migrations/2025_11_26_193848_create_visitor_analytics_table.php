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
        Schema::create('visitor_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 255)->index();
            $table->string('ip_address', 45); // IPv4 or IPv6
            $table->text('user_agent')->nullable();
            $table->string('page_url', 500);
            $table->string('referrer', 500)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city', 100)->nullable();
            $table->integer('visit_duration')->default(0)->comment('Duration in seconds');
            $table->integer('page_views')->default(1);
            $table->decimal('bounce_rate', 5, 2)->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();
            
            // Indexes for analytics queries
            $table->index('visited_at');
            $table->index(['country_code', 'city']);
            $table->index('ip_address');
        });
        
        // Add device_type column with enum type and default value
        DB::statement("ALTER TABLE visitor_analytics ADD COLUMN device_type device_enum DEFAULT 'desktop'::device_enum");
        DB::statement("CREATE INDEX visitor_analytics_device_type_index ON visitor_analytics (device_type)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_analytics');
    }
};
