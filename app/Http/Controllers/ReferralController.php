<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    /**
     * Display referral dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get referral statistics
        $totalReferrals = $user->referralsMade()->count();
        $pendingReferrals = $user->referralsMade()->pending()->count();
        $completedReferrals = $user->referralsMade()->completed()->count();
        $totalEarnings = $user->total_referral_earnings;
        $pendingEarnings = $user->pending_referral_earnings;
        
        // Get recent referrals with user details
        $referrals = $user->referralsMade()
            ->with('referredUser')
            ->latest()
            ->paginate(10);
        
        return view('referrals.index', compact(
            'user',
            'totalReferrals',
            'pendingReferrals',
            'completedReferrals',
            'totalEarnings',
            'pendingEarnings',
            'referrals'
        ));
    }

    /**
     * Generate or regenerate referral code
     */
    public function generateCode()
    {
        $user = Auth::user();
        
        if (!$user->referral_code) {
            $user->referral_code = User::generateReferralCode();
            $user->save();
        }
        
        return redirect()->back()->with('success', 'Referral code generated successfully!');
    }

    /**
     * Show referral statistics
     */
    public function statistics()
    {
        $user = Auth::user();
        
        $stats = [
            'total_referrals' => $user->referralsMade()->count(),
            'pending' => $user->referralsMade()->pending()->count(),
            'completed' => $user->referralsMade()->completed()->count(),
            'rewarded' => $user->referralsMade()->rewarded()->count(),
            'total_earnings' => $user->total_referral_earnings,
            'pending_earnings' => $user->pending_referral_earnings,
            'referral_link' => $user->referral_link,
        ];
        
        return response()->json($stats);
    }

    /**
     * Track referral click
     */
    public function trackClick(Request $request, $code)
    {
        $referrer = User::where('referral_code', $code)->first();
        
        if ($referrer) {
            // Store referral code in session for registration
            session(['referral_code' => $code]);
            
            return redirect()->route('register')->with('info', 'You were referred by ' . $referrer->name);
        }
        
        return redirect()->route('register');
    }

    /**
     * Process referral after successful registration
     */
    public static function processReferral($newUser, $referralCode)
    {
        $referrer = User::where('referral_code', $referralCode)->first();
        
        if ($referrer && $referrer->id !== $newUser->id) {
            // Update new user's referred_by field
            $newUser->referred_by = $referrer->id;
            $newUser->save();
            
            // Create referral record
            Referral::create([
                'referrer_id' => $referrer->id,
                'referred_user_id' => $newUser->id,
                'referral_code' => $referralCode,
                'status' => 'pending',
            ]);
            
            return true;
        }
        
        return false;
    }

    /**
     * Mark referral as completed (called when referred user makes first transaction)
     */
    public static function completeReferral($userId, $commissionAmount = 0)
    {
        $referral = Referral::where('referred_user_id', $userId)
            ->where('status', 'pending')
            ->first();
        
        if ($referral) {
            $referral->markAsCompleted($commissionAmount);
            
            // Optionally credit referrer's wallet
            if ($commissionAmount > 0 && $referral->referrer->wallet) {
                $referral->referrer->wallet->increment('balance', $commissionAmount);
                $referral->markAsRewarded();
            }
            
            return true;
        }
        
        return false;
    }

    /**
     * Process referral commission when a referred user completes a reference request
     * This pays 20% commission to the referrer
     */
    public static function processReferenceCommission($referenceId, $referenceAmount)
    {
        $reference = \App\Models\Reference::find($referenceId);
        if (!$reference) {
            return false;
        }

        // Get the student who made the reference request
        $student = $reference->student;
        
        // Check if this student was referred by someone
        if (!$student->referred_by) {
            return false;
        }

        // Get the referrer
        $referrer = User::find($student->referred_by);
        if (!$referrer) {
            return false;
        }

        // Calculate 20% commission
        $commissionRate = 0.20; // 20%
        $commissionAmount = $referenceAmount * $commissionRate;

        // Create a new referral commission record for this specific reference
        $referralCommission = Referral::create([
            'referrer_id' => $referrer->id,
            'referred_user_id' => $student->id,
            'reference_id' => $referenceId,
            'referral_code' => $student->referrer->referral_code ?? 'N/A',
            'status' => 'completed',
            'commission_amount' => $commissionAmount,
            'reference_amount' => $referenceAmount,
            'commission_paid' => false,
            'completed_at' => now(),
        ]);

        // Credit the referrer's wallet
        if ($referrer->wallet) {
            $referrer->wallet->add($commissionAmount);
            $referralCommission->update([
                'commission_paid' => true,
                'rewarded_at' => now(),
                'status' => 'rewarded',
            ]);

            // Send notification to referrer
            try {
                \Mail::to($referrer->email)->send(new \App\Mail\NotificationMail(
                    "Referral Commission Earned!",
                    "You've earned ₦" . number_format($commissionAmount, 2) . " (20% commission) from {$student->name}'s completed reference request. Your wallet has been credited.",
                    "View Referrals",
                    url('/referrals')
                ));
            } catch (\Exception $e) {
                \Log::error('Referral commission notification failed: ' . $e->getMessage());
            }

            return true;
        }

        return false;
    }
}
