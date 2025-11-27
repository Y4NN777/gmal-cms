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
        Schema::table('events', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('description');
            $table->timestamp('event_date')->nullable()->after('end_date');
            $table->boolean('is_featured')->default(false)->after('status');
            $table->integer('display_order')->default(0)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'event_date', 'is_featured', 'display_order']);
        });
    }
};
