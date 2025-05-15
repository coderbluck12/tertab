<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstitutionController extends Controller
{
    public function index(Request $request)
    {
        $query = Institution::with('state');

        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('state_id') && $request->state_id != '') {
            $query->where('state_id', $request->state_id);
        }

        $institutions = $query->get();
        $states = State::orderBy('name')->get();

        return view('institutions.index', compact('institutions', 'states'));
    }

    public function getByState($stateId = null)
    {
        try {
            $query = Institution::query();
            
            if ($stateId && $stateId !== 'null') {
                $query->where('state_id', $stateId);
            }
            
            $institutions = $query->orderBy('name')->get();
            
            if ($institutions->isEmpty()) {
                Log::warning('No institutions found for state ID: ' . $stateId);
            }
            
            return response()->json($institutions);
        } catch (\Exception $e) {
            Log::error('Error in getByState: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch institutions'], 500);
        }
    }
}
