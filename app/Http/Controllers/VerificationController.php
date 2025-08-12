<?php

namespace App\Http\Controllers;

use App\Mail\VerificationRejectedMail;
use App\Models\Document;
use App\Models\Notification;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function showVerificationRequired()
    {
        return view('verification.required');
    }

    public function submit(Request $request)
    {
        // Only allow students and lecturers
        if (!in_array(auth()->user()->role, ['student', 'lecturer'])) {
            return redirect()->back()->with('error', 'Only students and lecturers can submit verification requests.');
        }

        try {
            $request->validate([
                'document_type' => 'required|in:national_id,drivers_license,passport',
                'id_document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048'
            ]);

            $documentPath = $request->file('id_document')->store('verification_documents', 'public');

            // Check if user has existing verification request
            $existingRequest = VerificationRequest::where('user_id', auth()->id())->first();
            
            if ($existingRequest) {
                // Update existing request
                $existingRequest->update([
                    'document_type' => $request->document_type,
                    'document_path' => $documentPath,
                    'status' => 'pending',
                    'notes' => $request->notes,
                    'verification_name' => $request->document_type,
                    'rejection_reason' => null // Clear previous rejection reason
                ]);
                $verificationRequest = $existingRequest;
            } else {
                // Create new request
                $verificationRequest = VerificationRequest::create([
                    'user_id' => auth()->id(),
                    'document_type' => $request->document_type,
                    'document_path' => $documentPath,
                    'status' => 'pending',
                    'notes' => $request->notes,
                    'verification_name' => $request->document_type
                ]);
            }

            auth()->user()->update(['status' => 'pending']);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Verification request submitted successfully. We will review your documents shortly.'
                ]);
            }

            return redirect()->route('verification.required')
                ->with('success', 'Verification request submitted successfully. We will review your documents shortly.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Verification validation error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . collect($e->errors())->first()[0]
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Verification submission error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to submit verification request: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to submit verification request. Please try again.')
                ->withInput();
        }
    }

    public function approve(VerificationRequest $verificationRequest)
    {
        $verificationRequest->update([
            'status' => 'approved'
        ]);

        // Update user status to verified
        $verificationRequest->user->update([
            'status' => 'verified'
        ]);

        // Create notification for user
        $this->notificationService->send(
            $verificationRequest->user_id,
            'success',
            'Identity Verification Approved',
            'Congratulations! Your identity verification has been approved. You now have full access to all platform features.',
            route('dashboard')
        );

        return redirect()->back()->with('success', 'Verification request has been approved.');
    }

    public function reject(Request $request, VerificationRequest $verificationRequest)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $verificationRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        // Update user status to rejected so they can resubmit
        $verificationRequest->user->update([
            'status' => 'rejected'
        ]);

        // Send rejection email notification
        Mail::to($verificationRequest->user->email)->send(new VerificationRejectedMail($verificationRequest));

        // Create notification for user
        $this->notificationService->send(
            $verificationRequest->user_id,
            'error',
            'Identity Verification Rejected',
            'Your identity verification has been rejected. Please review the feedback and resubmit your documents.',
            route('verification.required')
        );

        return redirect()->back()->with('success', 'Verification request has been rejected.');
    }
} 