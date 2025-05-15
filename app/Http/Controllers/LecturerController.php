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

        $user = Auth::user();

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
            ->with(['attended.institution', 'attended.state']) // Include institution and state relationships
            ->select('id', 'name', 'role', 'phone', 'email', 'address', 'status')
            ->get();

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
}
