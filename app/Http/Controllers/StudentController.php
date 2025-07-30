<?php

namespace App\Http\Controllers;

use App\Models\InstitutionAttended;
use App\Models\PlatformSetting;
use App\Models\Reference;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lecturers = User::where('role', 'lecturer')->get();

        $user = Auth::user();

        $requests = Reference::where('student_id', Auth::id())->with('lecturer')->orderBy('created_at', 'desc')->paginate(9);

        // Count requests by status
        $requestCounts = [
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'lecturer approved')->count(),
            'rejected' => $requests->where('status', 'lecturer declined')->count(),
        ];

        $hasInstitution = $user->attended()->exists();

        return view('student.dashboard', compact('lecturers', 'requests', 'requestCounts', 'user', 'hasInstitution'));
    }

    /**
     * Show the form for creating a new resource.
     */
//    public function create()
//    {
//        $lecturers = User::where('role', 'lecturer')->get();
//
//        $requests = Reference::where('student_id', Auth::id())->with('lecturer')->get();
//
//        $settings = PlatformSetting::first();
//
//        $requestCounts = [
//            'pending' => $requests->where('status', 'pending')->count(),
//            'approved' => $requests->where('status', 'approved')->count(),
//            'rejected' => $requests->where('status', 'declined')->count(),
//        ];
//
//        return view('student.reference', compact('lecturers', 'requests', 'requestCounts', 'settings'));
//
//    }

    public function creates()
    {
        // Get only states where lecturers are registered
//        $states = State::whereHas('lecturers')->orderBy('name')->get();
        $states = State::orderBy('name')->get();

        $requests = Reference::where('student_id', Auth::id())->with('lecturer')->get();

        $settings = PlatformSetting::first();

        $lecturers = User::where('role', 'lecturer')->with('institution.state')->get();

        $requestCounts = [
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'approved')->count(),
            'rejected' => $requests->where('status', 'declined')->count(),
        ];

        return view('student.reference', compact('states', 'requests', 'requestCounts', 'lecturers', 'settings'));
    }

    public function create()
    {
        // Get the institutions the student has attended
//        $studentInstitutions = Auth::user()->attended()->with('state')->get();
        $studentInstitutions = InstitutionAttended::where('user_id', Auth::id())
            ->with(['institution', 'state'])
            ->get();

//        dd($studentInstitutions);
        // Get lecturers who belong to those institutions
        $lecturers = User::where('role', 'lecturer')
            ->whereHas('attended', function ($query) use ($studentInstitutions) {
                $query->whereIn('institution_id', $studentInstitutions->pluck('institution_id'));
            })
            ->with('attended.institution', 'attended.state')
            ->get();

        $settings = PlatformSetting::first();

        return view('student.reference', compact('studentInstitutions', 'lecturers', 'settings'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $request = Reference::with(['student', 'lecturer', 'institution', 'messages.sender'])->findOrFail($id);

        return view('student.show', compact('request'));
    }

    public function reference()
    {
        // Get the institutions the student has attended
        $studentInstitutions = InstitutionAttended::where('user_id', Auth::id())
            ->with(['institution', 'state'])
            ->get();

        // Get lecturers who belong to those institutions
        $lecturers = User::where('role', 'lecturer')
            ->whereHas('attended', function ($query) use ($studentInstitutions) {
                $query->whereIn('institution_id', $studentInstitutions->pluck('institution_id'));
            })
            ->with('attended.institution', 'attended.state')
            ->get();

        $settings = PlatformSetting::first();

        return view('student.reference', compact('studentInstitutions', 'lecturers', 'settings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $reference = Reference::where('student_id', Auth::id())->where('status', 'pending')->findOrFail($id);
        $lecturers = User::where('role', 'lecturer')->get();
        return view('student.edit-reference', compact('reference', 'lecturers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
