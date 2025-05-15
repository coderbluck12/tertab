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
        return view('references.create', compact('lecturers', 'settings'));
    }

    /**
     * Store a newly created reference request in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'lecturer_id' => 'required|exists:users,id',
                'reference_type' => 'required|in:document,email',
                'reference_email' => 'required_if:reference_type,email|email|nullable',
                'reference_description' => 'required|string'
            ]);

            // Create the reference
            $reference = Reference::create([
                'student_id' => Auth::id(),
                'lecturer_id' => $request->lecturer_id,
                'reference_type' => $request->reference_type,
                'reference_description' => $request->reference_description,
                'reference_email' => $request->reference_type === 'email' ? $request->reference_email : null,
                'status' => 'pending',
            ]);

            // Get the lecturer
            $lecturer = User::findOrFail($request->lecturer_id);
            
            // Send notification to lecturer
            $lecturerMessage = "A new reference request has been assigned to you. Please review the details in your dashboard.";
            if ($reference->reference_type === 'document') {
                $lecturerMessage .= " This is a document reference request. You will need to upload the reference document.";
            } else {
                $lecturerMessage .= " This is an email reference request. The reference should be sent to: " . $reference->reference_email;
            }

            $this->notificationService->send(
                $lecturer->id,
                'reference_request',
                'New Reference Request Assigned to You',
                $lecturerMessage,
                url('/lecturer/reference/' . $reference->id)
            );

            // Send notification to admins
            $adminMessage = "A new reference request has been submitted by a student. ";
            $adminMessage .= $reference->reference_type === 'document' 
                ? "This is a document reference request." 
                : "This is an email reference request to: " . $reference->reference_email;

            $this->notificationService->sendToAdmins(
                'reference_request',
                'New Reference Request Submitted',
                $adminMessage,
                url('/admin/reference/' . $reference->id)
            );

            return redirect()->route('student.dashboard')
                ->with('success', 'Reference request submitted successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Reference request error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to submit reference request. Please try again.']);
        }
    }


    /**
     * Show the form for editing a reference request.
     */
    public function edit($id)
    {
        $request = Reference::where('student_id', Auth::id())->where('status', 'pending')->findOrFail($id);
        return view('references.edit', compact('request'));
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
        $this->notificationService->send(
            $request->student_id,
            'reference_status',
            'Your Reference Request Has Been Approved',
            "Your reference request has been approved by {$request->lecturer->name}.",
            url('/student/reference/' . $id)
        );

        // Notify admins
        $this->notificationService->sendToAdmins(
            'reference_status',
            'Reference Request Approved',
            "{$request->lecturer->name} has approved a reference request for {$request->student->name}.",
            url('/admin/reference/' . $id)
        );

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

    public function confirm_email_sent(Request $request, $id)
    {
        $reference = Reference::with(['student', 'lecturer'])->findOrFail($id);

        $reference->status = 'lecturer email sent';
        $reference->save();

        Mail::to($reference->student->email)->send(new NotificationMail(
            "Lecturer Has Sent an Email",
            "Your lecturer, {$reference->lecturer->name}, has sent an email regarding your reference request.",
            "View Status",
            url('/student/dashboard')
        ));

        $admins = User::where('role', 'admin')->pluck('email');
        Mail::to($admins)->send(new NotificationMail(
            "Reference Email Sent",
            "{$reference->lecturer->name} has confirmed sending an email for a reference request for {$reference->student->name}.",
            "View Details",
            url('/admin/references')
        ));

        return redirect()->route('lecturer.dashboard')->with('success', 'Email sent to institution successfully.');
    }

    public function confirm_completed(Request $request, $id)
    {
        $reference = Reference::with(['student', 'lecturer'])->findOrFail($id);

        $reference->status = 'lecturer completed';
        $reference->save();

        Mail::to($reference->student->email)->send(new NotificationMail(
            "Reference Request Completed",
            "Your reference request has been marked as completed by {$reference->lecturer->name}.",
            "View Details",
            url('/student/dashboard')
        ));

        $admins = User::where('role', 'admin')->pluck('email');
        Mail::to($admins)->send(new NotificationMail(
            "Reference Request Completed",
            "{$reference->lecturer->name} has marked a reference request for {$reference->student->name} as completed.",
            "View Details",
            url('/admin/references')
        ));

        return redirect()->route('lecturer.dashboard')->with('success', 'Request marked completed successfully.');
    }

    public function mark_completed(Request $request, $id)
    {
        $reference = Reference::with(['student', 'lecturer'])->findOrFail($id);

        $reference->status = 'student confirmed';
        $reference->save();

        Mail::to($reference->lecturer->email)->send(new NotificationMail(
            "Student Confirmed Completion",
            "{$reference->student->name} has confirmed that their reference request is completed.",
            "View Details",
            url('/lecturer/dashboard')
        ));

        $admins = User::where('role', 'admin')->pluck('email');
        Mail::to($admins)->send(new NotificationMail(
            "Reference Request Finalized",
            "{$reference->student->name} has confirmed that their reference request with {$reference->lecturer->name} is finalized.",
            "View Details",
            url('/admin/references')
        ));

        return redirect()->route('lecturer.dashboard')->with('success', 'Request marked completed successfully.');
    }


    public function uploadDocument(Request $request, $id)
    {
        $reference = Reference::findOrFail($id);

        $request->validate([
            'document' => 'required|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('document')) {
            $documents = $request->file('document');

            if (!is_array($documents)) {
                $documents = [$documents];
            }

            foreach ($documents as $document) {

                $path = $document->store('reference_documents', 'public');

                $reference->document_path = $path;

                Document::create([
                    'user_id' => $reference->student_id,
                    'reference_id' => $id,
                    'path' => $path,
                    'type' => $reference->reference_type
                ]);
            }
        }

        Mail::to($reference->student->email)->send(new NotificationMail(
            "Reference Document Uploaded",
            "A document has been uploaded for your reference request by {$reference->lecturer->name}.",
            "Download Document",
            url('/student/reference/' . $id)
        ));

        $admins = User::where('role', 'admin')->pluck('email');
        Mail::to($admins)->send(new NotificationMail(
            "Reference Document Uploaded",
            "A document has been uploaded for a reference request involving {$reference->student->name} and {$reference->lecturer->name}.",
            "View Document",
            url('/admin/reference/' . $id)
        ));

//        if ($reference->reference_type == 'email') {
//            Mail::to($reference->student->email)->send(new ReferenceDocumentMail($reference));
//        }

        return redirect()->route('lecturer.dashboard')->with('success', 'Document uploaded and sent successfully.');
    }

}
