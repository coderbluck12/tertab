<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $lecturer = auth()->user();

        $requests = Reference::where('lecturer_id', $lecturer->id)->orderBy('created_at', 'desc')->paginate(10);

        $user = Auth::user()->load('verificationRequest');

        $lecturerStats = [
            'pending' => $lecturer->requests()->where('status', 'pending')->count(),
            'approved' => $lecturer->requests()->where('status', 'lecturer approved')->count(),
            'rejected' => $lecturer->requests()->where('status', 'lecturer declined')->count(),
            'awaiting' => $lecturer->requests()->where('status', 'lecturer completed')->count(),
            'total' => $lecturer->requests()->count(),
        ];
        return view('lecturer.dashboard', compact('requests', 'lecturerStats', 'user'));
    }

    public function getLecturersByInstitution($institution_id)
    {
        $lecturers = User::where('role', 'lecturer')
            ->whereHas('attended', function ($query) use ($institution_id) {
                $query->where('institution_id', $institution_id);
            })
            ->with(['attended' => function ($query) use ($institution_id) {
                $query->where('institution_id', $institution_id);
            }, 'attended.institution', 'attended.state'])
            ->select('id', 'name', 'role', 'phone', 'email', 'address', 'status')
            ->get()
            ->map(function ($lecturer) use ($institution_id) {
                // Get only the specific institution data
                $specificInstitution = $lecturer->attended->where('institution_id', $institution_id)->first();
                
                return [
                    'id' => $lecturer->id,
                    'name' => $lecturer->name,
                    'role' => $lecturer->role,
                    'phone' => $lecturer->phone,
                    'email' => $lecturer->email,
                    'address' => $lecturer->address,
                    'status' => $lecturer->status,
                    'institution' => $specificInstitution ? [
                        'name' => $specificInstitution->institution->name,
                        'position' => $specificInstitution->position,
                        'state' => $specificInstitution->state ? $specificInstitution->state->name : null
                    ] : null
                ];
            });

        return response()->json($lecturers);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $request =  Reference::with('student.state', 'student.institution')->findOrFail($id);

        $documents = $request->student->documents()
            ->where('type', 'verification')
            ->get();

        $reference_documents = $request->documents()->get();
        $user = User::with('state', 'institution')->findOrFail($request->student_id);
        $institutions = $user->attended()->with('documents', 'state', 'institution')->get();
//dd($user);
        return view('lecturer.show', compact('request', 'documents', 'reference_documents', 'institutions', 'user'));
    }


    public function getLecturersForReferenceByState($stateId)
    {
        $lecturers = User::where('role', 'lecturer')
            ->where('state_id', $stateId)
            ->orderBy('name')
            ->get();

        return response()->json($lecturers);
    }

    /**
     * Display dedicated references page for lecturers.
     */
    public function references()
    {
        $lecturer = auth()->user();
        $requests = Reference::where('lecturer_id', $lecturer->id)
            ->with('student')
            ->latest()
            ->paginate(10);

        // Count requests by status
        $stats = [
            'total' => Reference::where('lecturer_id', $lecturer->id)->count(),
            'pending' => Reference::where('lecturer_id', $lecturer->id)->where('status', 'pending')->count(),
            'approved' => Reference::where('lecturer_id', $lecturer->id)->where('status', 'lecturer approved')->count(),
            'completed' => Reference::where('lecturer_id', $lecturer->id)->where('status', 'lecturer completed')->count(),
            'rejected' => Reference::where('lecturer_id', $lecturer->id)->where('status', 'lecturer rejected')->count(),
        ];

        return view('lecturer.references', compact('requests', 'stats', 'lecturer'));
    }
}
