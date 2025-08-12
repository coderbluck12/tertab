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
        // Step 1: Drop any existing foreign key constraints
        try {
            DB::statement('ALTER TABLE `references` DROP FOREIGN KEY IF EXISTS `references_institution_id_foreign`');
        } catch (Exception $e) {
            // Constraint might not exist
        }

        // Step 2: Clean up invalid data
        DB::statement('
            UPDATE `references` 
            SET `institution_id` = NULL 
            WHERE `institution_id` IS NOT NULL 
            AND `institution_id` NOT IN (SELECT `id` FROM `institutions`)
        ');

        // Step 3: Ensure the column has the correct data type
        if (Schema::hasColumn('references', 'institution_id')) {
            Schema::table('references', function (Blueprint $table) {
                $table->unsignedBigInteger('institution_id')->nullable()->change();
            });
        } else {
            Schema::table('references', function (Blueprint $table) {
                $table->unsignedBigInteger('institution_id')->nullable()->after('lecturer_id');
            });
        }

        // Step 4: Add the foreign key constraint using raw SQL for better control
        DB::statement('
            ALTER TABLE `references` 
            ADD CONSTRAINT `references_institution_id_foreign` 
            FOREIGN KEY (`institution_id`) 
            REFERENCES `institutions` (`id`) 
            ON DELETE SET NULL 
            ON UPDATE CASCADE
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('references', function (Blueprint $table) {
            $table->dropForeign(['institution_id']);
        });
    }
};
