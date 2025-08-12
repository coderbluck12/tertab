<?php

/**
 * Migration Fix Script
 * This script helps resolve the foreign key constraint violation issue
 * Run this script before running your migrations
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Starting migration fix process...\n\n";

try {
    // Check if references table exists
    if (!Schema::hasTable('references')) {
        echo "✅ References table doesn't exist yet. You can run migrations normally.\n";
        exit(0);
    }

    // Check if institution_id column exists
    if (!Schema::hasColumn('references', 'institution_id')) {
        echo "✅ Institution_id column doesn't exist yet. You can run migrations normally.\n";
        exit(0);
    }

    echo "📊 Analyzing existing data...\n";

    // Count total references
    $totalReferences = DB::table('references')->count();
    echo "   - Total references: {$totalReferences}\n";

    // Count references with null institution_id
    $nullInstitutionRefs = DB::table('references')->whereNull('institution_id')->count();
    echo "   - References with null institution_id: {$nullInstitutionRefs}\n";

    // Count references with invalid institution_id
    $invalidInstitutionRefs = DB::table('references')
        ->whereNotNull('institution_id')
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('institutions')
                  ->whereRaw('institutions.id = references.institution_id');
        })
        ->count();
    echo "   - References with invalid institution_id: {$invalidInstitutionRefs}\n";

    if ($invalidInstitutionRefs > 0) {
        echo "\n🔧 Fixing invalid institution_id values...\n";
        
        // Set invalid institution_id values to null
        $updated = DB::table('references')
            ->whereNotNull('institution_id')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('institutions')
                      ->whereRaw('institutions.id = references.institution_id');
            })
            ->update(['institution_id' => null]);
        
        echo "   - Fixed {$updated} invalid references\n";
    }

    // Try to populate missing institution_id from lecturer's institution
    echo "\n🔧 Attempting to populate missing institution_id values...\n";
    
    $populated = DB::table('references as r')
        ->join('users as u', 'r.lecturer_id', '=', 'u.id')
        ->join('institution_attended as ia', 'u.id', '=', 'ia.user_id')
        ->whereNull('r.institution_id')
        ->whereNotNull('ia.institution_id')
        ->update(['r.institution_id' => DB::raw('ia.institution_id')]);
    
    echo "   - Populated {$populated} references with institution data\n";

    // Drop existing foreign key constraint if it exists
    echo "\n🔧 Checking for existing foreign key constraints...\n";
    
    try {
        Schema::table('references', function ($table) {
            $table->dropForeign(['institution_id']);
        });
        echo "   - Dropped existing foreign key constraint\n";
    } catch (Exception $e) {
        echo "   - No existing foreign key constraint found\n";
    }

    echo "\n✅ Migration fix completed successfully!\n";
    echo "💡 You can now run 'php artisan migrate' safely.\n";

} catch (Exception $e) {
    echo "\n❌ Error occurred: " . $e->getMessage() . "\n";
    echo "💡 Please check your database connection and try again.\n";
    exit(1);
}
