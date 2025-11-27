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
        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('slug', 255)->unique();
            $table->foreignId('cover_image_id')->nullable()->constrained('media')->onDelete('set null');
            $table->boolean('is_featured')->default(false);
            $table->integer('display_order')->default(0);
            $table->jsonb('settings')->nullable()->comment('Album display settings');
            $table->timestamps();
            
            // Indexes
            $table->index('slug');
            $table->index('is_featured');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_albums');
    }
};
