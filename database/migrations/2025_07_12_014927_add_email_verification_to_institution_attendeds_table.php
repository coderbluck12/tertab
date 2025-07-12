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
        Schema::table('institution_attendeds', function (Blueprint $table) {
            $table->string('school_email')->nullable()->after('institution_id');
            $table->timestamp('email_verified_at')->nullable()->after('school_email');
            $table->string('email_verification_token')->nullable()->after('email_verified_at');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->after('email_verification_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_attendeds', function (Blueprint $table) {
            $table->dropColumn(['school_email', 'email_verified_at', 'email_verification_token', 'status']);
        });
    }
};
