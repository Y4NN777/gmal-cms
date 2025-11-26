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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description');
            $table->string('short_description', 500)->nullable();
            $table->foreignId('featured_image_id')->nullable()->constrained('media')->onDelete('set null');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->string('location', 255)->nullable();
            $table->foreignId('category_id')->constrained('categories');
            $table->enum('status', ['draft', 'published', 'archived', 'cancelled'])->default('draft');
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->jsonb('meta_data')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
