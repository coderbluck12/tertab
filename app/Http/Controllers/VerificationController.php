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
    public function submit(Request $request)
    {
        $request->validate([
            'verification_name' => ['required', 'string', 'max:255'],
            'school_email' => ['required', 'email', 'max:255'],
            'institution' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'id_card' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'additional_documents.*' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            // Create verification request
            $verificationRequest = VerificationRequest::create([
                'user_id' => Auth::id(),
                'verification_name' => $request->verification_name,
                'school_email' => $request->school_email,
                'institution' => $request->institution,
                'position' => $request->position,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            // Store ID card
            $idCardPath = $request->file('id_card')->store('verification/id_cards', 'public');
            $idCardDocument = Document::create([
                'user_id' => Auth::id(),
                'verification_request_id' => $verificationRequest->id,
                'path' => $idCardPath,
                'name' => 'ID Card',
                'type' => 'id_card'
            ]);

            \Log::info('ID Card Document Created:', [
                'id' => $idCardDocument->id,
                'path' => $idCardPath,
                'verification_request_id' => $verificationRequest->id
            ]);

            // Store additional documents if any
            if ($request->hasFile('additional_documents')) {
                foreach ($request->file('additional_documents') as $document) {
                    $path = $document->store('verification/additional_documents', 'public');
                    $additionalDocument = Document::create([
                        'user_id' => Auth::id(),
                        'verification_request_id' => $verificationRequest->id,
                        'path' => $path,
                        'name' => 'Additional Document',
                        'type' => 'additional_document'
                    ]);

                    \Log::info('Additional Document Created:', [
                        'id' => $additionalDocument->id,
                        'path' => $path,
                        'verification_request_id' => $verificationRequest->id
                    ]);
                }
            }

            return redirect()->route('dashboard')->with('status', 'Verification request submitted successfully. We will review your documents and get back to you soon.');
        } catch (\Exception $e) {
            \Log::error('Verification Request Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'There was an error submitting your verification request. Please try again.');
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