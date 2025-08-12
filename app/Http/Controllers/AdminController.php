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

        $requests = Reference::with('student')->latest()->paginate(10);
        $verificationRequests = VerificationRequest::with('user')->latest()->paginate(10);

        return view('admin.dashboard', compact('adminStats', 'requests', 'verificationRequests'));
    }

    /**
     * Display all reference requests with pagination.
     */
    public function allReferences()
    {
        $requests = Reference::with(['student', 'lecturer'])
            ->latest()
            ->paginate(15);

        return view('admin.references', compact('requests'));
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

    /**
     * Display institutions for admin management
     */
    public function institutions()
    {
        // Load states for the form dropdown
        $states = \App\Models\State::all();
        
        // Return view without institutions - they will be loaded via AJAX from the API
        return view('admin.institutions.index', compact('states'));
    }

    /**
     * Store a new institution
     */
    public function storeInstitution(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'ownership' => 'required|string|max:255',
        ]);

        \App\Models\Institution::create([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'slug' => \Str::slug($request->name),
            'ownership' => $request->ownership,
        ]);

        return redirect()->route('admin.institutions.index')
            ->with('success', 'Institution created successfully.');
    }

    /**
     * Update an existing institution
     */
    public function updateInstitution(Request $request, \App\Models\Institution $institution)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
            'ownership' => 'required|string|max:255',
        ]);

        $institution->update([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'slug' => \Str::slug($request->name),
            'ownership' => $request->ownership,
        ]);

        return redirect()->route('admin.institutions.index')
            ->with('success', 'Institution updated successfully.');
    }

    /**
     * Delete an institution
     */
    public function destroyInstitution(\App\Models\Institution $institution)
    {
        $institution->delete();

        return redirect()->route('admin.institutions.index')
            ->with('success', 'Institution deleted successfully.');
    }

    /**
     * Display courses for admin management
     */
    public function courses()
    {
        // Since Course model doesn't exist yet, return empty view for now
        $courses = collect();
        
        return view('admin.courses', compact('courses'));
    }

    /**
     * Store a new course
     */
    public function storeCourse(Request $request)
    {
        // Placeholder for when Course model is created
        return redirect()->route('admin.courses.index')
            ->with('info', 'Course management not yet implemented.');
    }

    /**
     * Delete a course
     */
    public function destroyCourse($course)
    {
        // Placeholder for when Course model is created
        return redirect()->route('admin.courses.index')
            ->with('info', 'Course management not yet implemented.');
    }

    /**
     * Export students data to Excel/CSV
     */
    public function exportStudents()
    {
        $students = User::where('role', 'student')->get();
        
        $filename = 'students_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Name', 
                'Email',
                'Status',
                'Email Verified',
                'Joined Date',
                'Last Updated',
                'Wallet Balance',
                'References Count',
                'Institutions Count'
            ]);
            
            // Add data rows
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $student->email,
                    $student->status ?? 'pending',
                    $student->email_verified_at ? 'Yes' : 'No',
                    $student->created_at->format('Y-m-d H:i:s'),
                    $student->updated_at->format('Y-m-d H:i:s'),
                    number_format($student->wallet_balance ?? 0, 2),
                    $student->references_count ?? 0,
                    $student->institutions_count ?? 0
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export lecturers data to Excel/CSV
     */
    public function exportLecturers()
    {
        $lecturers = User::where('role', 'lecturer')->get();
        
        $filename = 'lecturers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($lecturers) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Name',
                'Email', 
                'Status',
                'Email Verified',
                'Joined Date',
                'Last Updated',
                'Wallet Balance',
                'References Provided',
                'Institutions Count'
            ]);
            
            // Add data rows
            foreach ($lecturers as $lecturer) {
                fputcsv($file, [
                    $lecturer->id,
                    $lecturer->name,
                    $lecturer->email,
                    $lecturer->status ?? 'pending',
                    $lecturer->email_verified_at ? 'Yes' : 'No',
                    $lecturer->created_at->format('Y-m-d H:i:s'),
                    $lecturer->updated_at->format('Y-m-d H:i:s'),
                    number_format($lecturer->wallet_balance ?? 0, 2),
                    $lecturer->references_provided_count ?? 0,
                    $lecturer->institutions_count ?? 0
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export reference requests data to Excel/CSV
     */
    public function exportReferences()
    {
        $references = Reference::with(['student', 'lecturer'])->get();
        
        $filename = 'reference_requests_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($references) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Request ID',
                'Student Name',
                'Student Email',
                'Lecturer Name',
                'Lecturer Email',
                'Request Type',
                'Reference Type',
                'Status',
                'Amount',
                'Description',
                'Created Date',
                'Last Updated',
                'Documents Count'
            ]);
            
            // Add data rows
            foreach ($references as $reference) {
                fputcsv($file, [
                    $reference->id,
                    $reference->student->name ?? 'N/A',
                    $reference->student->email ?? 'N/A',
                    $reference->lecturer->name ?? 'N/A',
                    $reference->lecturer->email ?? 'N/A',
                    $reference->request_type ?? 'N/A',
                    $reference->reference_type ?? 'N/A',
                    $reference->status ?? 'pending',
                    number_format($reference->amount ?? 0, 2),
                    $reference->reference_description ?? 'No description',
                    $reference->created_at->format('Y-m-d H:i:s'),
                    $reference->updated_at->format('Y-m-d H:i:s'),
                    $reference->documents ? $reference->documents->count() : 0
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

}
