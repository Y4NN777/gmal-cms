<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('donation_analytics', function (Blueprint $table) {
            // Add transaction_id for Pawapay deposit IDs
            $table->string('transaction_id', 100)->nullable()->unique()->after('payment_reference');
            
            // Add index for faster lookups
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_analytics', function (Blueprint $table) {
            $table->dropIndex(['transaction_id']);
            $table->dropColumn('transaction_id');
        });
    }
};
