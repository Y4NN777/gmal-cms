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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 255)->nullable();
            $table->string('position', 100)->nullable();
            $table->string('organization', 100)->nullable();
            $table->text('content');
            $table->smallInteger('rating')->default(5)->comment('1-5 star rating');
            $table->foreignId('avatar_id')->nullable()->constrained('media')->onDelete('set null');
            $table->boolean('is_featured')->default(false);
            $table->integer('display_order')->default(0);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('is_featured');
            $table->index('display_order');
        });
        
        // Add status column with enum type and default value
        DB::statement("ALTER TABLE testimonials ADD COLUMN status testimonial_status_enum DEFAULT 'pending'::testimonial_status_enum");
        DB::statement("CREATE INDEX testimonials_status_index ON testimonials (status)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
