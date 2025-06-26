<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    public function showVerificationRequired()
    {
        return view('verification.required');
    }

    public function submit(Request $request)
    {
        try {
            $request->validate([
                'document_type' => 'required|in:national_id,drivers_license,passport',
                'id_document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048'
            ]);

            $documentPath = $request->file('id_document')->store('verification_documents', 'public');

            $verificationRequest = VerificationRequest::create([
                'user_id' => auth()->id(),
                'document_type' => $request->document_type,
                'document_path' => $documentPath,
                'status' => 'pending',
                'notes' => $request->notes,
                'verification_name' => $request->document_type
            ]);

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

        return redirect()->back()->with('success', 'Verification request has been rejected.');
    }
} 