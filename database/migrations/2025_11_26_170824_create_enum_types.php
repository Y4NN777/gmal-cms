<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE TYPE user_role_enum AS ENUM ('admin', 'editor', 'viewer')");
        DB::statement("CREATE TYPE event_status_enum AS ENUM ('draft', 'published', 'archived', 'cancelled')");
        DB::statement("CREATE TYPE testimonial_status_enum AS ENUM ('pending', 'approved', 'rejected')");
        DB::statement("CREATE TYPE device_enum AS ENUM ('desktop', 'mobile', 'tablet')");
        DB::statement("CREATE TYPE donation_type_enum AS ENUM ('one_time', 'monthly', 'yearly')");
        DB::statement("CREATE TYPE payment_status_enum AS ENUM ('pending', 'completed', 'failed', 'refunded')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TYPE IF EXISTS user_role_enum");
        DB::statement("DROP TYPE IF EXISTS event_status_enum");
        DB::statement("DROP TYPE IF EXISTS testimonial_status_enum");
        DB::statement("DROP TYPE IF EXISTS device_enum");
        DB::statement("DROP TYPE IF EXISTS donation_type_enum");
        DB::statement("DROP TYPE IF EXISTS payment_status_enum");
    }
};
