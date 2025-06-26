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
        Schema::table('verification_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('verification_requests', 'document_path')) {
                $table->string('document_path')->after('document_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('verification_requests', function (Blueprint $table) {
            if (Schema::hasColumn('verification_requests', 'document_path')) {
                $table->dropColumn('document_path');
            }
        });
    }
}; 