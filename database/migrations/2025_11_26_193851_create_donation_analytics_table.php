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
        Schema::create('donation_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('donor_name', 255);
            $table->string('email', 255)->index();
            $table->string('phone', 20)->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->foreignId('campaign_id')->nullable()->constrained('events')->onDelete('set null');
            $table->string('payment_method', 50);
            $table->string('payment_reference', 100)->nullable()->unique();
            $table->jsonb('donor_metadata')->nullable()->comment('Additional donor information');
            $table->timestamps();
            
            // Indexes for reporting
            $table->index('created_at');
        });
        
        // Add enum columns with default values
        DB::statement("ALTER TABLE donation_analytics ADD COLUMN donation_type donation_type_enum DEFAULT 'one_time'::donation_type_enum");
        DB::statement("ALTER TABLE donation_analytics ADD COLUMN status payment_status_enum DEFAULT 'pending'::payment_status_enum");
        DB::statement("CREATE INDEX donation_analytics_donation_type_index ON donation_analytics (donation_type)");
        DB::statement("CREATE INDEX donation_analytics_status_index ON donation_analytics (status)");
        DB::statement("CREATE INDEX donation_analytics_campaign_status_index ON donation_analytics (campaign_id, status)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_analytics');
    }
};
