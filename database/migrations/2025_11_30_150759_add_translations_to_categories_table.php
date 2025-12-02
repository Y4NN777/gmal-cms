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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_en', 100)->nullable()->after('name');
            $table->string('name_fr', 100)->nullable()->after('name_en');
            $table->text('description_en')->nullable()->after('description');
            $table->text('description_fr')->nullable()->after('description_en');
        });

        // Migrate existing data to both language columns
        DB::table('categories')->get()->each(function ($category) {
            DB::table('categories')
                ->where('id', $category->id)
                ->update([
                    'name_en' => $category->name,
                    'name_fr' => $category->name, // Default to EN, will be translated later
                    'description_en' => $category->description,
                    'description_fr' => $category->description,
                ]);
        });

        // Make columns NOT NULL after populating data
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_en', 100)->nullable(false)->change();
            $table->string('name_fr', 100)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'name_fr', 'description_en', 'description_fr']);
        });
    }
};
