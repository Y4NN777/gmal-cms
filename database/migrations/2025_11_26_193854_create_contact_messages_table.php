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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->index();
            $table->string('phone', 20)->nullable();
            $table->string('subject', 255);
            $table->text('message');
            $table->string('status')->default('unread'); // unread, read, replied, archived
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('replied_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('internal_notes')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('status');
            $table->index('priority');
            $table->index('created_at');
            $table->index(['status', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
