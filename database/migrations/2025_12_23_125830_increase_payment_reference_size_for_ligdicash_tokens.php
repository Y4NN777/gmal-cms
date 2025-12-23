<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Increase payment_reference size from 100 to 500 characters
     * to accommodate Ligdicash JWT tokens which can be 200-300 chars
     */
    public function up(): void
    {
        Schema::table('donation_analytics', function (Blueprint $table) {
            $table->string('payment_reference', 500)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donation_analytics', function (Blueprint $table) {
            $table->string('payment_reference', 100)->change();
        });
    }
};
