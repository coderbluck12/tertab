<?php

namespace App\Http\Controllers;

use App\Mail\AffiliateApprovedMail;
use App\Mail\AffiliateRejectedMail;
use App\Models\AffiliateApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AffiliateController extends Controller
{
    /**
     * Show affiliate application page
     */
    public function index()
    {
        return view('affiliate.index');
    }

    /**
     * Store affiliate application
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:affiliate_applications,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'reason' => 'required|string|min:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate temporary password
        $temporaryPassword = 'Tertab' . rand(1000, 9999) . '!';

        // Create user account for affiliate immediately
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'affiliate',
            'password' => Hash::make($temporaryPassword),
            'status' => 'active',
            'referral_code' => User::generateReferralCode(),
        ]);

        // Create application record with approved status
        $application = AffiliateApplication::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'reason' => $request->reason,
            'status' => 'approved',
            'user_id' => $user->id,
            'approved_at' => now(),
        ]);

        // Send email to affiliate with login credentials
        try {
            Mail::to($request->email)->send(new AffiliateApprovedMail($user, $temporaryPassword));
        } catch (\Exception $e) {
            \Log::error('Affiliate approval email failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Your affiliate account has been created successfully! Login credentials have been sent to your email.');
    }

    /**
     * Show all affiliate applications (Admin)
     */
    public function adminIndex(Request $request)
    {
        $query = AffiliateApplication::with('user');
        
        // Filter by status
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $applications = $query->latest()->paginate(20);

        return view('admin.affiliates.index', compact('applications'));
    }

    /**
     * Show single affiliate application (Admin)
     */
    public function show($id)
    {
        $application = AffiliateApplication::findOrFail($id);
        return response()->json($application);
    }

    /**
     * Approve affiliate application (Admin)
     * Note: Applications are now auto-approved, but this method remains for edge cases
     */
    public function approve(Request $request, $id)
    {
        $application = AffiliateApplication::findOrFail($id);

        if ($application->status === 'approved') {
            return redirect()->back()->with('info', 'Application already approved. User account was created automatically.');
        }

        // Generate temporary password
        $temporaryPassword = 'Tertab' . rand(1000, 9999) . '!';

        // Create user account for affiliate
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
        $application->approve($request->admin_notes);
        $application->user_id = $user->id;
        $application->save();

        // Send email to affiliate with login credentials
        try {
            Mail::to($application->email)->send(new AffiliateApprovedMail($user, $temporaryPassword));
        } catch (\Exception $e) {
            \Log::error('Affiliate approval email failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Affiliate application approved and account created successfully! Login credentials have been sent to their email.');
    }

    /**
     * Reject affiliate application (Admin)
     */
    public function reject(Request $request, $id)
    {
        $application = AffiliateApplication::findOrFail($id);

        if ($application->status === 'rejected') {
            return redirect()->back()->with('error', 'Application already rejected.');
        }

        $application->reject($request->admin_notes);

        // Send rejection email to applicant
        try {
            Mail::to($application->email)->send(new AffiliateRejectedMail($application, $request->admin_notes));
        } catch (\Exception $e) {
            \Log::error('Affiliate rejection email failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Affiliate application rejected and notification sent.');
    }

    /**
     * Delete affiliate application (Admin)
     */
    public function destroy($id)
    {
        $application = AffiliateApplication::findOrFail($id);
        $application->delete();

        return redirect()->back()->with('success', 'Affiliate application deleted successfully.');
    }
}
