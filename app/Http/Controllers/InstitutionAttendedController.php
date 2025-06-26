<?php

namespace App\Http\Controllers;

use App\Models\InstitutionAttended;
use App\Models\PlatformSetting;
use App\Models\State;
use Illuminate\Http\Request;

class InstitutionAttendedController extends Controller
{
    public function index()
    {
        $institutions = auth()->user()->attended()->with('documents', 'state', 'institution')->get();
        $settings = PlatformSetting::first();
        $states = State::orderBy('name')->get();
//dd($institutions);
        return view('institution_attended.index', compact('institutions', 'settings', 'states'));
    }

    public function create()
    {
        return view('institutions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'state' => 'required',
            'institution' => 'required',
            'type' => 'required',
            'field_of_study' => 'nullable|string',
            'position' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048'
        ]);

        $institutionAttended = InstitutionAttended::create([
            'user_id' => auth()->id(),
            'state_id' => $request->state,
            'institution_id' => $request->institution,
            'type' => $request->type,
            'field_of_study' => $request->field_of_study,
            'position' => $request->position,
            'start_date' => $request->start_date,
            'end_date' => $request->filled('end_date') ? $request->end_date : null,
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('institution_documents', 'public');

                $institutionAttended->documents()->create([
                    'user_id' => auth()->id(),
                    'path' => $path,
                    'type' => 'institution',
                    'institution_attended_id' => $institutionAttended->id
                ]);
            }
        }

        return redirect()->route('institution.attended.show')->with('success', 'Institution added successfully!');
    }

    public function show()
    {
        $institutions = auth()->user()->attended()->with('documents', 'state', 'institution')->get();
        $settings = PlatformSetting::first();
        $states = State::orderBy('name')->get();
        return view('institution_attended.index', compact('institutions', 'settings', 'states'));
    }
}
