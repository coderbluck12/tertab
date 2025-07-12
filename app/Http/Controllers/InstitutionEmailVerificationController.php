<?php

namespace App\Http\Controllers;

use App\Models\InstitutionAttended;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InstitutionEmailVerificationController extends Controller
{
    /**
     * Send verification email for an institution
     */
    public function sendVerificationEmail(InstitutionAttended $institution)
    {
        // Check if user owns this institution
        if ($institution->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if already verified
        if ($institution->isVerified()) {
            return back()->with('info', 'This institution is already verified.');
        }

        // Generate verification token
        $token = $institution->generateVerificationToken();

        // Send verification email
        try {
            Mail::send('emails.institution-verification', [
                'institution' => $institution,
                'token' => $token,
                'user' => auth()->user()
            ], function ($message) use ($institution) {
                $message->to($institution->school_email)
                        ->subject('Verify Your Institution Email - Tertab');
            });

            return back()->with('success', 'Verification email sent to ' . $institution->school_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send verification email. Please try again.');
        }
    }

    /**
     * Verify email token
     */
    public function verify(Request $request, $token)
    {
        $institution = InstitutionAttended::where('email_verification_token', $token)->first();

        if (!$institution) {
            return redirect()->route('institution.attended.show')
                           ->with('error', 'Invalid verification token.');
        }

        // Mark as verified
        $institution->markAsVerified();

        return redirect()->route('institution.attended.show')
                       ->with('success', 'Institution email verified successfully!');
    }

    /**
     * Resend verification email
     */
    public function resend(InstitutionAttended $institution)
    {
        return $this->sendVerificationEmail($institution);
    }
}
