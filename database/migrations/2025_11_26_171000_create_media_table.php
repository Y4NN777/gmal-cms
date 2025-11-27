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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 255);
            $table->string('original_name', 255);
            $table->string('mime_type', 100);
            $table->bigInteger('size');
            $table->jsonb('dimensions')->nullable();
            $table->string('alt_text', 255)->nullable();
            $table->text('caption')->nullable();
            $table->foreignId('folder_id')->nullable(); // assuming folder table exists or nullable
            $table->string('storage_path', 500)->nullable();
            $table->string('cdn_url', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
