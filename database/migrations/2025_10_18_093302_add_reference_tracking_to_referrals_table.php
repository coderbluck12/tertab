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
        Schema::table('referrals', function (Blueprint $table) {
            $table->foreignId('reference_id')->nullable()->after('referred_user_id')->constrained('references')->onDelete('cascade');
            $table->decimal('reference_amount', 10, 2)->default(0)->after('commission_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropForeign(['reference_id']);
            $table->dropColumn(['reference_id', 'reference_amount']);
        });
    }
};
