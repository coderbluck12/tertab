<?php

namespace App\Http\Controllers;

use App\Mail\NotificationMail;
use App\Models\Reference;
use App\Models\User;
use App\Models\VerificationRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adminStats = [
            'lecturers' => User::where('role', 'lecturer')->count(),
            'students' => User::where('role', 'student')->count(),
            'pending' => Reference::where('status', 'pending')->count(),
            'approved' => Reference::where('status', 'lecturer approved')->count(),
            'rejected' => Reference::where('status', 'lecturer completed')->count(),
            'awaiting' => Reference::where('status', 'lecturer rejected')->count(),
            'total' => Reference::count(),
            'verification_requests' => VerificationRequest::where('status', 'pending')->count(),
        ];

        $requests = Reference::with('student')->latest()->get();
        $verificationRequests = VerificationRequest::with('user')->latest()->get();

        return view('admin.dashboard', compact('adminStats', 'requests', 'verificationRequests'));
    }

    /**
     * Display the specified resource.
     */
    public function shows(string $id)
    {
        $request = Reference::with([
            'lecturer.documents' => function($query) {
                $query->where('type', 'verification');
            },
            'student.documents' => function($query) {
                $query->where('type', 'verification');
            },
            'documents', 'student.state', 'student.attended'])->findOrFail($id);
dd($request);
        return view('admin.show', compact('request'));
    }

    public function shos(string $id)
    {
        $request = Reference::with([
            'lecturer', // Load the lecturer directly
            'lecturer.documents' => function ($query) {
                $query->where('type', 'verification');
            },
            'lecturer.attended',
            'student' => function ($query) {
                $query->with(['attended' => function ($q) {
                    $q->select('id', 'name'); // Load only the attended institution
                }]);
            },
            'student.documents' => function ($query) {
                $query->where('type', 'verification');
            },
            'student.attended',
            'documents' // Load the documents associated with the reference
        ])->findOrFail($id);
        dd($request);
        return view('admin.show', compact('request'));
    }

    public function show(string $id)
    {
        $request = Reference::with([
            'lecturer' => function ($query) {
                $query->with(['attended', 'documents' => function ($q) {
                    $q->where('type', 'verification');
                }]);
            },
            'student' => function ($query) {
                $query->with(['attended', 'documents' => function ($q) {
                    $q->where('type', 'verification');
                }]);
            },
            'documents'
        ])->findOrFail($id);

        // Add debugging
        \Log::info('Reference Request Data:', [
            'id' => $request->id,
            'reference_type' => $request->reference_type,
            'request_type' => $request->request_type,
            'status' => $request->status,
            'description' => $request->reference_description,
            'student' => $request->student ? [
                'id' => $request->student->id,
                'name' => $request->student->name,
                'email' => $request->student->email
            ] : null,
            'lecturer' => $request->lecturer ? [
                'id' => $request->lecturer->id,
                'name' => $request->lecturer->name,
                'email' => $request->lecturer->email
            ] : null
        ]);

        return view('admin.show', compact('request'));
    }



    public function students()
    {
        $students = User::where('role', 'student')->paginate(10);
        return view('admin.students', compact('students'));
    }

    public function lecturers()
    {
        $lecturers = User::where('role', 'lecturer')->paginate(10);
        return view('admin.lecturers', compact('lecturers'));
    }

    public function showUser($id)
    {
        $user = User::with([
            'state', 
            'institution',
            'verificationRequest' => function($query) {
                $query->with('documents');
            }
        ])->findOrFail($id);
        
        $institutions = $user->attended()->with('documents', 'state', 'institution')->get();
        
        // Debug information
        if ($user->verificationRequest) {
            \Log::info('Verification Request:', [
                'id' => $user->verificationRequest->id,
                'documents_count' => $user->verificationRequest->documents->count(),
                'documents' => $user->verificationRequest->documents->toArray()
            ]);
        }
        
        return view('admin.user-profile', compact('user', 'institutions'));
    }

    public function approveUser($id)
    {
        // Find the student by ID
        $student = User::findOrFail($id);

        // Update status to "verified"
        $student->update(['status' => 'verified']);

        // Send notification
        $this->notificationService->send(
            $student->id,
            'account_status',
            'Account Approved',
            'Your account has been approved. You can now access the platform.',
            url('/login'),
            true
        );

        return redirect()->route('admin.dashboard')->with('success', 'Student approved successfully.');
    }

    public function DeactivateUser($id)
    {
        // Find the student by ID
        $student = User::findOrFail($id);

        // Update status to "deactivated"
        $student->update(['status' => 'deactivated']);

        // Send notification
        $this->notificationService->send(
            $student->id,
            'account_status',
            'Account Deactivated',
            'Your account has been deactivated. If this was a mistake, please contact the administrator.',
            url('/login'),
            true
        );

        return redirect()->route('admin.dashboard')->with('success', 'Student deactivated successfully.');
    }

    public function verificationRequests()
    {
        $verificationRequests = VerificationRequest::with(['user', 'documents'])
            ->latest()
            ->paginate(10);
            
        return view('admin.verification-requests', compact('verificationRequests'));
    }

}
