<?php

namespace App\Http\Controllers;

use App\Models\InstitutionAttended;
use App\Models\PlatformSetting;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InstitutionAttendedController extends Controller
{
    public function index()
    {
        $institutions = auth()->user()->attended()->with('documents', 'state', 'institution')->get();
        $settings = PlatformSetting::first();
        $states = State::orderBy('name')->get();
        return view('institution_attended.index', compact('institutions', 'settings', 'states'));
    }

    public function create()
    {
        $states = State::orderBy('name')->get();
        return view('institution_attended.create', compact('states'));
    }

    public function store(Request $request)
    {
        $whitelistedEmails = ['circusayop@gmail.com', 'oyenolaphilip89@gmail.com'];
        $data = $request->validate([
            'state' => 'required',
            'institution' => 'required',
            'type' => 'required',
            'field_of_study' => 'nullable|string',
            'position' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
            'school_email' => [
                'nullable',
                'email',
                function (
                    $attribute,
                    $value,
                    $fail
                ) use ($whitelistedEmails) {
                    if ($value && !in_array(strtolower($value), $whitelistedEmails) && !preg_match('/\.(edu|edu\.ng)$/', $value)) {
                        $fail('The school email must be a valid .edu or .edu.ng email address.');
                    }
                }
            ]
        ]);

        // For lecturers, require school email
        if ($request->type === 'lecturer') {
            $request->validate([
                'school_email' => [
                    'required',
                    'email',
                    function (
                        $attribute,
                        $value,
                        $fail
                    ) use ($whitelistedEmails) {
                        if (!in_array(strtolower($value), $whitelistedEmails) && !preg_match('/\.(edu|edu\.ng)$/', $value)) {
                            $fail('The school email must be a valid .edu or .edu.ng email address.');
                        }
                    }
                ]
            ], [
                'school_email.required' => 'School email is required for lecturers.'
            ]);
        }

        $institutionAttended = InstitutionAttended::create([
            'user_id' => auth()->id(),
            'state_id' => $request->state,
            'institution_id' => $request->institution,
            'type' => $request->type,
            'field_of_study' => $request->field_of_study,
            'position' => $request->position,
            'start_date' => $request->start_date,
            'end_date' => $request->filled('end_date') ? $request->end_date : null,
            'school_email' => $request->school_email,
            'status' => $request->type === 'lecturer' ? 'pending' : 'verified',
        ]);

        // Handle document uploads (append to any existing documents)
        if ($request->hasFile('documents')) {
            \Log::info('Documents found in request', ['count' => count($request->file('documents'))]);
            
            foreach ($request->file('documents') as $file) {
                // Generate unique filename to avoid conflicts
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = pathinfo($originalName, PATHINFO_FILENAME);
                $uniqueFilename = $filename . '_' . time() . '_' . uniqid() . '.' . $extension;
                
                $path = $file->storeAs('institution_documents', $uniqueFilename, 'public');
                
                \Log::info('Storing document', [
                    'original_name' => $originalName,
                    'stored_name' => $uniqueFilename,
                    'path' => $path,
                    'institution_id' => $institutionAttended->id
                ]);

                try {
                    $document = $institutionAttended->documents()->create([
                        'user_id' => auth()->id(),
                        'path' => $path,
                        'name' => $originalName, // Keep original name for display
                        'type' => 'institution',
                        'institution_attended_id' => $institutionAttended->id
                    ]);
                    
                    \Log::info('Document created successfully', ['document_id' => $document->id]);
                } catch (\Exception $e) {
                    \Log::error('Failed to create document', [
                        'error' => $e->getMessage(),
                        'file' => $originalName
                    ]);
                }
            }
        } else {
            \Log::info('No documents found in request');
        }

        // Send verification email for lecturers
        if ($request->type === 'lecturer' && $request->school_email) {
            try {
                $token = $institutionAttended->generateVerificationToken();
                
                Mail::send('emails.institution-verification', [
                    'institution' => $institutionAttended,
                    'token' => $token,
                    'user' => auth()->user()
                ], function ($message) use ($institutionAttended) {
                    $message->to($institutionAttended->school_email)
                            ->subject('Verify Your Institution Email - Tertab');
                });

                return redirect()->route('institution.attended.show')
                               ->with('success', 'Institution added successfully! Please check your school email to verify your account.');
            } catch (\Exception $e) {
                return redirect()->route('institution.attended.show')
                               ->with('success', 'Institution added successfully! However, we could not send the verification email. Please contact support.');
            }
        }

        return redirect()->route('institution.attended.show')->with('success', 'Institution added successfully!');
    }

    public function show()
    {
        $institutions = auth()->user()->attended()->with('documents', 'state', 'institution')->get();
        
        // Debug: Log the institutions and their documents
        foreach ($institutions as $institution) {
            \Log::info('Institution loaded', [
                'id' => $institution->id,
                'name' => $institution->institution->name ?? 'N/A',
                'documents_count' => $institution->documents->count(),
                'documents' => $institution->documents->map(function($doc) {
                    return ['id' => $doc->id, 'name' => $doc->name, 'path' => $doc->path];
                })->toArray()
            ]);
        }
        
        $settings = PlatformSetting::first();
        $states = State::orderBy('name')->get();
        return view('institution_attended.index', compact('institutions', 'settings', 'states'));
    }

    public function edit(InstitutionAttended $institutionAttended)
    {
        // Debug logging for production troubleshooting
        \Log::info('Institution Edit Access Attempt', [
            'institution_id' => $institutionAttended->id,
            'institution_user_id' => $institutionAttended->user_id,
            'current_user_id' => auth()->id(),
            'user_authenticated' => auth()->check(),
            'user_email' => auth()->user()->email ?? 'not authenticated',
            'institution_user_id_type' => gettype($institutionAttended->user_id),
            'current_user_id_type' => gettype(auth()->id()),
            'strict_comparison' => $institutionAttended->user_id === auth()->id(),
            'loose_comparison' => $institutionAttended->user_id == auth()->id()
        ]);
        
        // Check if user is authenticated
        if (!auth()->check()) {
            \Log::warning('User not authenticated when accessing institution edit');
            abort(401, 'Authentication required.');
        }
        
        // Check if user owns this institution (both students and lecturers can edit institutions they added)
        // Using loose comparison (==) to handle string/integer user_id differences
        if ($institutionAttended->user_id != auth()->id()) {
            \Log::warning('Unauthorized institution edit attempt', [
                'institution_id' => $institutionAttended->id,
                'institution_owner_id' => $institutionAttended->user_id,
                'current_user_id' => auth()->id(),
                'current_user_email' => auth()->user()->email,
                'current_user_role' => auth()->user()->role
            ]);
            abort(403, 'Unauthorized action. You can only edit institutions you added.');
        }

        $states = State::orderBy('name')->get();
        return view('institution_attended.edit', compact('institutionAttended', 'states'));
    }

    public function update(Request $request, InstitutionAttended $institutionAttended)
    {
        // Debug logging for production troubleshooting
        \Log::info('Institution Update Access Attempt', [
            'institution_id' => $institutionAttended->id,
            'institution_user_id' => $institutionAttended->user_id,
            'current_user_id' => auth()->id(),
            'user_authenticated' => auth()->check(),
            'user_email' => auth()->user()->email ?? 'not authenticated'
        ]);
        
        // Check if user is authenticated
        if (!auth()->check()) {
            \Log::warning('User not authenticated when updating institution');
            abort(401, 'Authentication required.');
        }
        
        // Check if user owns this institution (both students and lecturers can edit institutions they added)
        // Using loose comparison (==) to handle string/integer user_id differences
        if ($institutionAttended->user_id != auth()->id()) {
            \Log::warning('Unauthorized institution update attempt', [
                'institution_id' => $institutionAttended->id,
                'institution_owner_id' => $institutionAttended->user_id,
                'current_user_id' => auth()->id(),
                'current_user_email' => auth()->user()->email,
                'current_user_role' => auth()->user()->role
            ]);
            abort(403, 'Unauthorized action. You can only update institutions you added.');
        }

        $whitelistedEmails = ['circusayop@gmail.com', 'oyenolaphilip89@gmail.com'];
        $data = $request->validate([
            'state' => 'required',
            'institution' => 'required',
            'type' => 'required',
            'field_of_study' => 'nullable|string',
            'position' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
            'school_email' => [
                'nullable',
                'email',
                function (
                    $attribute,
                    $value,
                    $fail
                ) use ($whitelistedEmails) {
                    if ($value && !in_array(strtolower($value), $whitelistedEmails) && !preg_match('/\.(edu|edu\.ng)$/', $value)) {
                        $fail('The school email must be a valid .edu or .edu.ng email address.');
                    }
                }
            ]
        ]);

        // For lecturers, require school email
        if ($request->type === 'lecturer') {
            $request->validate([
                'school_email' => [
                    'required',
                    'email',
                    function (
                        $attribute,
                        $value,
                        $fail
                    ) use ($whitelistedEmails) {
                        if (!in_array(strtolower($value), $whitelistedEmails) && !preg_match('/\.(edu|edu\.ng)$/', $value)) {
                            $fail('The school email must be a valid .edu or .edu.ng email address.');
                        }
                    }
                ]
            ], [
                'school_email.required' => 'School email is required for lecturers.'
            ]);
        }

        // Update institution data
        $institutionAttended->update([
            'state_id' => $request->state,
            'institution_id' => $request->institution,
            'type' => $request->type,
            'field_of_study' => $request->field_of_study,
            'position' => $request->position,
            'start_date' => $request->start_date,
            'end_date' => $request->filled('end_date') ? $request->end_date : null,
            'school_email' => $request->school_email,
        ]);

        // Handle new document uploads (append to existing)
        if ($request->hasFile('documents')) {
            \Log::info('Documents found in update request', ['count' => count($request->file('documents'))]);
            
            foreach ($request->file('documents') as $file) {
                $path = $file->store('institution_documents', 'public');
                
                \Log::info('Storing new document', [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'institution_id' => $institutionAttended->id
                ]);

                try {
                    $document = $institutionAttended->documents()->create([
                        'user_id' => auth()->id(),
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                        'type' => 'institution',
                        'institution_attended_id' => $institutionAttended->id
                    ]);
                    
                    \Log::info('Document created successfully', ['document_id' => $document->id]);
                } catch (\Exception $e) {
                    \Log::error('Failed to create document', [
                        'error' => $e->getMessage(),
                        'file' => $file->getClientOriginalName()
                    ]);
                }
            }
        }

        return redirect()->route('institution.attended.show')
                        ->with('success', 'Institution updated successfully!');
    }

    public function deleteDocument(InstitutionAttended $institutionAttended, $documentId)
    {
        // Check if user owns this institution
        // Using loose comparison (!=) to handle string/integer user_id differences
        if ($institutionAttended->user_id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $document = $institutionAttended->documents()->findOrFail($documentId);
            
            // Delete file from storage
            if (\Storage::disk('public')->exists($document->path)) {
                \Storage::disk('public')->delete($document->path);
            }
            
            // Delete document record
            $document->delete();

            return response()->json(['success' => true, 'message' => 'Document deleted successfully.']);
        } catch (\Exception $e) {
            \Log::error('Failed to delete document', [
                'document_id' => $documentId,
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to delete document.'], 500);
        }
    }

    public function destroy(InstitutionAttended $institutionAttended)
    {
        // Check if user owns this institution
        // Using loose comparison (!=) to handle string/integer user_id differences
        if ($institutionAttended->user_id != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete associated documents from storage
            foreach ($institutionAttended->documents as $document) {
                if (\Storage::disk('public')->exists($document->path)) {
                    \Storage::disk('public')->delete($document->path);
                }
                $document->delete();
            }

            // Delete the institution
            $institutionAttended->delete();

            return redirect()->route('institution.attended.show')
                           ->with('success', 'Institution deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to delete institution', [
                'institution_id' => $institutionAttended->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('institution.attended.show')
                           ->with('error', 'Failed to delete institution. Please try again.');
        }
    }

    /**
     * Display a listing of user's institutions
     */
    public function list()
    {
        $user = auth()->user();
        $institutions = $user->attended()
            ->with('documents', 'state', 'institution')
            ->latest()
            ->paginate(10);

        // Count institutions by type
        $stats = [
            'total' => $user->attended()->count(),
            'verified' => $user->attended()->whereNotNull('email_verified_at')->count(),
            'pending' => $user->attended()->whereNull('email_verified_at')->count(),
        ];

        return view('institution_attended.list', compact('institutions', 'stats', 'user'));
    }

    /**
     * Send verification email for institution attendance.
     */
    public function sendVerificationEmail(InstitutionAttended $institutionAttended)
    {
        // Check if user owns this institution record
        if ($institutionAttended->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this institution record.');
        }

        // Check if it's a lecturer type that needs verification
        if ($institutionAttended->type !== 'lecturer') {
            return back()->with('error', 'Only lecturer institutions require email verification.');
        }

        // Check if already verified
        if ($institutionAttended->isVerified()) {
            return back()->with('info', 'This institution is already verified.');
        }

        // Check if school email exists
        if (!$institutionAttended->school_email) {
            return back()->with('error', 'School email is required for verification.');
        }

        try {
            // Generate verification token if not exists
            if (!$institutionAttended->verification_token) {
                $institutionAttended->verification_token = \Str::random(64);
                $institutionAttended->save();
            }

            // Send verification email
            \Mail::to($institutionAttended->school_email)->send(new \App\Mail\InstitutionVerificationMail(
                $institutionAttended,
                route('institution.email.verify', [
                    'institution' => $institutionAttended->id,
                    'token' => $institutionAttended->verification_token
                ])
            ));

            return back()->with('success', 'Verification email sent to ' . $institutionAttended->school_email);
        } catch (\Exception $e) {
            \Log::error('Institution verification email error: ' . $e->getMessage());
            return back()->with('error', 'Failed to send verification email. Please try again.');
        }
    }

    /**
     * Verify email for institution attendance.
     */
    public function verifyEmail($institutionId, $token)
    {
        $institution = InstitutionAttended::where('id', $institutionId)
            ->where('verification_token', $token)
            ->first();

        if (!$institution) {
            return redirect()->route('institution.attended.index')
                ->with('error', 'Invalid verification link or token has expired.');
        }

        if ($institution->isVerified()) {
            return redirect()->route('institution.attended.index')
                ->with('info', 'This institution is already verified.');
        }

        // Mark as verified
        $institution->markAsVerified();

        return redirect()->route('institution.attended.index')
            ->with('success', 'Institution email verified successfully!');
    }
}
