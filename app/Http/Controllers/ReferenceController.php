<?php

namespace App\Http\Controllers;

use App\Mail\NotificationMail;
use App\Mail\ReferenceDocumentMail;
use App\Models\Document;
use App\Models\PlatformSetting;
use App\Models\Reference;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReferenceController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the reference requests (for students).
     */
    public function index()
    {
        $lecturers = User::where('role', 'lecturer')->get();
        $requests = Reference::where('student_id', Auth::id())->with('lecturer')->get();

        return view('dashboard.student', compact('lecturers', 'requests'));
    }

    /**
     * Show the form for creating a new reference request.
     */
    public function create()
    {
        $lecturers = User::where('role', 'lecturer')->get();
        $settings = PlatformSetting::first();
        $studentInstitutions = auth()->user()->institutionsAttended()->with(['institution', 'state'])->get();
        return view('student.reference', compact('lecturers', 'settings', 'studentInstitutions'));
    }

    /**
     * Store a newly created reference request in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'institution_id' => 'required|exists:institutions,id',
            'lecturer_id' => 'required|exists:users,id',
            'reference_type' => 'required|in:email,document',
            'request_type' => 'required|in:normal,express',
            'reference_description' => 'required|string',
        ]);

        // Get the current reference request price
        $settings = PlatformSetting::first();
        if (!$settings) {
            return back()->with('error', 'Platform settings not configured. Please contact support.');
        }
        $price = $request->request_type === 'express' ? $settings->express_reference_request_price : $settings->reference_request_price;

        // Check if user has sufficient funds
        $wallet = auth()->user()->wallet;
        if (!$wallet || !$wallet->hasSufficientFunds($price)) {
            // Store the form data in session for later use
            session(['pending_reference_request' => $request->all()]);
            
            return redirect()->route('wallet.show')
                ->with('error', 'Insufficient funds. Please fund your wallet to proceed with the reference request. Your form data has been saved.');
        }

        // Deduct the amount from wallet
        if (!$wallet->deduct($price)) {
            return back()->with('error', 'Failed to process payment. Please try again.');
        }

        $reference = Reference::create([
            'student_id' => auth()->id(),
            'lecturer_id' => $request->lecturer_id,
            'institution_id' => $request->institution_id,
            'reference_type' => $request->reference_type,
            'request_type' => $request->request_type,
            'reference_description' => $request->reference_description,
            'status' => 'pending',
        ]);
        
        // Send notification to lecturer
        $lecturer = User::find($request->lecturer_id);
        try {
            Mail::to($lecturer->email)->send(new NotificationMail(
                "New Reference Request",
                "You have received a new reference request from " . auth()->user()->name . ".",
                "View Request",
                url('/lecturer/dashboard')
            ));
        } catch (\Exception $e) {
            // Log the error but continue with the request
            \Log::error('Mail sending error: ' . $e->getMessage());
        }

        // Send notification to admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new NotificationMail(
                    "New Reference Request",
                    "A new reference request has been submitted by " . auth()->user()->name . " to " . $lecturer->name . ".",
                    "View Request",
                    url('/admin/references')
                ));
            } catch (\Exception $e) {
                // Log the error but continue with the request
                \Log::error('Mail sending error: ' . $e->getMessage());
            }
        }

        return redirect()->route('student.dashboard')
        ->with('success', 'Reference request submitted successfully.');
    }

    /**
     * Show the form for editing a reference request.
     */
    public function edit($id)
    {
        $reference = Reference::where('student_id', Auth::id())->where('status', 'pending')->findOrFail($id);
        $lecturers = User::where('role', 'lecturer')->get();
        return view('student.edit-reference', compact('reference', 'lecturers'));
    }

    /**
     * Update the specified reference request in storage.
     */
    public function update(Request $request, $id)
    {
        $reference = Reference::where('student_id', Auth::id())->where('status', 'pending')->findOrFail($id);

        $request->validate([
            'lecturer_id' => 'required|exists:users,id',
            'reference_type' => 'required|in:document,email'
        ]);

        $reference->update([
            'lecturer_id' => $request->lecturer_id,
            'reference_type' => $request->reference_type,
            'reference_description' => $request->reference_description,
            'status' => 'pending',
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Reference request updated successfully.');
    }

    /**
     * Remove the specified reference request from storage.
     */
    public function destroy($id)
    {
        $reference = Reference::where('student_id', Auth::id())->where('status', 'pending')->findOrFail($id);
        $reference->delete();

        return redirect()->route('student.dashboard')->with('success', 'Reference request deleted.');
    }

    public function approve($id)
    {
        $request = Reference::with(['lecturer', 'student'])->findOrFail($id);
        $request->status = 'lecturer approved';
        $request->save();

        // Notify student
        try {
            $this->notificationService->send(
                $request->student_id,
                'reference_status',
                'Your Reference Request Has Been Approved',
                "Your reference request has been approved by {$request->lecturer->name}.",
                route('student.reference.show', $id)
            );
        } catch (\Exception $e) {
            \Log::error('Notification error (student): ' . $e->getMessage());
        }

        // Notify admins
        try {
            $this->notificationService->sendToAdmins(
                'reference_status',
                'Reference Request Approved',
                "{$request->lecturer->name} has approved a reference request for {$request->student->name}.",
                route('admin.reference.show', $id)
            );
        } catch (\Exception $e) {
            \Log::error('Notification error (admin): ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Reference request approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $reference = Reference::with(['lecturer', 'student'])->findOrFail($id);

        $reference->status = 'lecturer declined';
        $reference->reference_rejection_reason = $request->input('reference_rejection_reason');
        $reference->save();

        // Notify student
        $this->notificationService->send(
            $reference->student_id,
            'reference_status',
            'Your Reference Request Has Been Declined',
            "Your reference request has been declined by {$reference->lecturer->name}. Reason: {$reference->reference_rejection_reason}",
            url('/student/dashboard')
        );

        // Notify admins
        $this->notificationService->sendToAdmins(
            'reference_status',
            'Reference Request Declined',
            "{$reference->lecturer->name} has declined a reference request for {$reference->student->name}. Reason: {$reference->reference_rejection_reason}",
            url('/admin/references')
        );

        return redirect()->route('lecturer.dashboard')->with('success', 'Reference request rejected successfully.');
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $reference = Reference::findOrFail($id);
        $path = $request->file('document')->store('reference_documents', 'public');

        $reference->update([
            'status' => 'document_uploaded',
            'document_path' => $path,
        ]);

        // Send a notification to the student
        try {
            Mail::to($reference->student->email)->send(new ReferenceDocumentMail($reference));
        } catch (\Exception $e) {
            \Log::error('Mail sending error: ' . $e->getMessage());
        }

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function confirm_email_sent(Request $request, $id)
    {
        $reference = Reference::with(['student', 'lecturer'])->findOrFail($id);

        $reference->status = 'completed';
        if ($reference->request_type == 'express') {
            $price = PlatformSetting::first()->express_reference_request_price;
        } else {
            $price = PlatformSetting::first()->reference_request_price;
        }

        // Credit lecturer's wallet
        $lecturerWallet = $reference->lecturer->wallet;
        $lecturerWallet->credit($price);
        $reference->payment_processed = true;
        $reference->save();

        // Notify lecturer of payment
        try {
            Mail::to($reference->lecturer->email)->send(new NotificationMail(
                "Payment Received for Reference Request",
                "You have received ₦" . number_format($price, 2) . " for completing the reference request for {$reference->student->name}. Your wallet has been credited.",
                "View Wallet",
                url('/wallet')
            ));
        } catch (\Exception $e) {
            \Log::error('Payment notification email failed to send: ' . $e->getMessage());
        }

        // Notify student
        try {
            Mail::to($reference->student->email)->send(new NotificationMail(
                "Lecturer Has Sent an Email",
                "Your lecturer, {$reference->lecturer->name}, has sent an email regarding your reference request.",
                "View Status",
                url('/student/dashboard')
            ));
        } catch (\Exception $e) {
            \Log::error('Mail sending error: ' . $e->getMessage());
        }

        // Notify admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new NotificationMail(
                    "Reference Email Sent",
                    "{$reference->lecturer->name} has confirmed sending an email for a reference request for {$reference->student->name}.",
                    "View Details",
                    url('/admin/references')
                ));
            } catch (\Exception $e) {
                \Log::error('Mail sending error: ' . $e->getMessage());
            }
        }

        return redirect()->route('lecturer.dashboard')->with('success', 'Email sent to institution successfully.');
    }

    public function confirm_completed(Request $request, $id)
    {
        $reference = Reference::with(['student', 'lecturer'])->findOrFail($id);

        $reference->status = 'lecturer completed';
        $reference->save();

        try {
            Mail::to($reference->student->email)->send(new NotificationMail(
                "Reference Request Completed",
                "Your reference request has been marked as completed by {$reference->lecturer->name}.",
                "View Details",
                url('/student/dashboard')
            ));
        } catch (\Exception $e) {
            \Log::error('Mail sending error: ' . $e->getMessage());
        }

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new NotificationMail(
                    "Reference Request Completed",
                    "{$reference->lecturer->name} has marked a reference request for {$reference->student->name} as completed.",
                    "View Details",
                    url('/admin/references')
                ));
            } catch (\Exception $e) {
                \Log::error('Mail sending error: ' . $e->getMessage());
            }
        }

        return redirect()->route('lecturer.dashboard')->with('success', 'Request marked completed successfully.');
    }

    public function mark_completed(Request $request, $id)
    {
        $reference = Reference::with(['student', 'lecturer'])->findOrFail($id);

        // Check if payment has already been processed
        if ($reference->payment_processed) {
            return redirect()->route('student.dashboard')->with('error', 'Payment has already been processed for this reference.');
        }

        $reference->status = 'student confirmed';
        $reference->payment_processed = true; // Mark as paid
        $reference->save();

        // Credit the lecturer's wallet
        $lecturer = $reference->lecturer;
        $lecturerWallet = $lecturer->wallet;
        
        if (!$lecturerWallet) {
            // Create wallet if it doesn't exist
            $lecturerWallet = \App\Models\Wallet::create([
                'user_id' => $lecturer->id,
                'balance' => 0,
                'currency' => 'NGN'
            ]);
        }

        // Calculate the amount to credit (same as what was deducted from student)
        $settings = PlatformSetting::first();
        $amount = $reference->request_type === 'express' ? $settings->express_reference_request_price : $settings->reference_request_price;
        
        // Credit the lecturer's wallet
        $lecturerWallet->add($amount);

        // Send email notification to lecturer about payment
        try {
            Mail::to($lecturer->email)->send(new \App\Mail\NotificationMail(
                "Payment Received for Reference Request",
                "You have received ₦" . number_format($amount, 2) . " for completing the reference request for {$reference->student->name}. Your wallet has been credited.",
                "View Wallet",
                url('/wallet')
            ));
        } catch (\Exception $e) {
            \Log::error('Payment notification email failed to send: ' . $e->getMessage());
        }

        try {
            Mail::to($reference->lecturer->email)->send(new NotificationMail(
                "Student Confirmed Completion",
                "{$reference->student->name} has confirmed that their reference request is completed.",
                "View Details",
                url('/lecturer/dashboard')
            ));
        } catch (\Exception $e) {
            \Log::error('Mail sending error: ' . $e->getMessage());
        }

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new NotificationMail(
                    "Reference Request Finalized",
                    "{$reference->student->name} has confirmed that their reference request with {$reference->lecturer->name} is finalized. Payment of ₦" . number_format($amount, 2) . " has been credited to {$reference->lecturer->name}'s wallet.",
                    "View Details",
                    url('/admin/references')
                ));
            } catch (\Exception $e) {
                \Log::error('Mail sending error: ' . $e->getMessage());
            }
        }

        return redirect()->route('student.dashboard')->with('success', 'Reference request confirmed and payment processed successfully.');
    }

    /**
     * Send a message from lecturer to student regarding a reference request
     */
    public function sendMessage(Request $request, $id)
    {
        $reference = Reference::findOrFail($id);
        
        // Check if the current user is the lecturer for this reference
        if (auth()->id() !== $reference->lecturer_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        // Create the message
        $message = \App\Models\ReferenceMessage::create([
            'reference_id' => $reference->id,
            'sender_id' => auth()->id(),
            'message' => $request->message
        ]);

        // Send notification to student (you can implement email notification here if needed)
        $this->notificationService->send(
            $reference->student_id,
            'message',
            'New message from lecturer',
            "You have received a new message from {$reference->lecturer->name} regarding your reference request.",
            route('student.reference.show', $reference->id)
        );

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    /**
     * Send message from student to lecturer
     */
    public function sendStudentMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $reference = Reference::findOrFail($id);
        
        // Ensure the student can only send messages for their own references
        if ($reference->student_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Create the message
        \App\Models\ReferenceMessage::create([
            'reference_id' => $reference->id,
            'sender_id' => auth()->id(),
            'message' => $request->message
        ]);

        // Send notification to lecturer
        $this->notificationService->send(
            $reference->lecturer_id,
            'message',
            'New message from student',
            "You have received a new message from {$reference->student->name} regarding their reference request.",
            route('lecturer.reference.show', $reference->id)
        );

        return redirect()->back()->with('success', 'Message sent successfully!');
    }

}
