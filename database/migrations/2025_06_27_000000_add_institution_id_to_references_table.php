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
        if (!Schema::hasColumn('references', 'institution_id')) {
            Schema::table('references', function (Blueprint $table) {
                $table->unsignedBigInteger('institution_id')->nullable()->after('lecturer_id');
                $table->foreign('institution_id')->references('id')->on('institutions')->onDelete('cascade');
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
                $table->dropColumn('institution_id');
            });
        }
    }
}; 