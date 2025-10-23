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
        // Add referral_code to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code')->unique()->nullable()->after('status');
            $table->foreignId('referred_by')->nullable()->constrained('users')->onDelete('set null')->after('referral_code');
        });

        // Create referrals table to track all referrals
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referred_user_id')->constrained('users')->onDelete('cascade');
            $table->string('referral_code');
            $table->enum('status', ['pending', 'completed', 'rewarded'])->default('pending');
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->boolean('commission_paid')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('rewarded_at')->nullable();
            $table->timestamps();
            
            $table->index('referrer_id');
            $table->index('referred_user_id');
            $table->index('referral_code');
        });

        // Create affiliate_applications table for non-registered users
        Schema::create('affiliate_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index('email');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_applications');
        Schema::dropIfExists('referrals');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referral_code', 'referred_by']);
        });
    }
};
