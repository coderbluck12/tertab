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
        // First, let's check if the institution_id column exists
        if (Schema::hasColumn('references', 'institution_id')) {
            // Drop the existing foreign key constraint if it exists
            try {
                Schema::table('references', function (Blueprint $table) {
                    $table->dropForeign(['institution_id']);
                });
            } catch (Exception $e) {
                // Foreign key might not exist, continue
            }

            // Clean up invalid institution_id values
            // Set institution_id to NULL for records that don't have valid institution references
            DB::statement('
                UPDATE `references` 
                SET `institution_id` = NULL 
                WHERE `institution_id` IS NOT NULL 
                AND `institution_id` NOT IN (SELECT `id` FROM `institutions`)
            ');

            // For references that don't have institution_id but should have one,
            // we can try to infer it from the lecturer's institution if available
            try {
                DB::statement('
                    UPDATE `references` r
                    INNER JOIN `users` u ON r.lecturer_id = u.id
                    INNER JOIN `institution_attendeds` ia ON u.id = ia.user_id
                    SET r.institution_id = ia.institution_id
                    WHERE r.institution_id IS NULL
                    AND ia.institution_id IS NOT NULL
                    LIMIT 1
                ');
            } catch (Exception $e) {
                // Table might not exist yet, skip this step
            }

            // Ensure the column is the correct data type
            Schema::table('references', function (Blueprint $table) {
                $table->unsignedBigInteger('institution_id')->nullable()->change();
            });

            // Now add the foreign key constraint with proper handling of NULLs
            Schema::table('references', function (Blueprint $table) {
                $table->foreign('institution_id', 'references_institution_id_foreign')
                      ->references('id')
                      ->on('institutions')
                      ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('references', 'institution_id')) {
            Schema::table('references', function (Blueprint $table) {
                $table->dropForeign(['institution_id']);
            });
        }
    }
};
