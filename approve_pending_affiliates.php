<?php
/**
 * Quick script to approve pending affiliate applications
 * Run this with: php approve_pending_affiliates.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AffiliateApplication;
use App\Models\User;
use App\Mail\AffiliateApprovedMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

// Get all pending applications
$pendingApplications = AffiliateApplication::where('status', 'pending')->get();

if ($pendingApplications->isEmpty()) {
    echo "No pending affiliate applications found.\n";
    exit;
}

echo "Found " . $pendingApplications->count() . " pending application(s):\n\n";

foreach ($pendingApplications as $application) {
    echo "Processing: {$application->name} ({$application->email})\n";
    
    // Check if user already exists
    $existingUser = User::where('email', $application->email)->first();
    
    if ($existingUser) {
        echo "  ⚠ User already exists with this email. Skipping...\n\n";
        continue;
    }
    
    // Generate temporary password
    $temporaryPassword = 'Tertab' . rand(1000, 9999) . '!';
    
    // Create user account
    $user = User::create([
        'name' => $application->name,
        'email' => $application->email,
        'phone' => $application->phone,
        'role' => 'affiliate',
        'password' => Hash::make($temporaryPassword),
        'status' => 'active',
        'referral_code' => User::generateReferralCode(),
    ]);
    
    // Update application
    $application->update([
        'status' => 'approved',
        'user_id' => $user->id,
        'approved_at' => now(),
    ]);
    
    echo "  ✓ User account created\n";
    echo "  ✓ Referral code: {$user->referral_code}\n";
    echo "  ✓ Temporary password: {$temporaryPassword}\n";
    
    // Send email
    try {
        Mail::to($application->email)->send(new AffiliateApprovedMail($user, $temporaryPassword));
        echo "  ✓ Email sent successfully\n\n";
    } catch (\Exception $e) {
        echo "  ⚠ Email failed: " . $e->getMessage() . "\n";
        echo "  → Manual password: {$temporaryPassword}\n\n";
    }
}

echo "Done!\n";
