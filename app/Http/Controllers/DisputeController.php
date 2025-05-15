<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Reference;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    // Show the form to create a new dispute
    public function create($reference_id)
    {
        $reference = Reference::findOrFail($reference_id);
        return view('dispute.create', compact('reference'));
    }

    // Store a new dispute
    public function store(Request $request)
    {
        $request->validate([
            'reference_id' => 'required|exists:references,id', // Fix table name
            'message' => 'required|string',
        ]);

        $dispute = Dispute::create([
            'reference_id' => $request->reference_id,
            'created_by' => auth()->id(),
            'status' => 'open',
        ]);

        // Store the first dispute message
        $dispute->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->route('disputes.show', $dispute->id)->with('success', 'Dispute created successfully.');
    }

    // Show dispute details and messages
    public function show(Dispute $dispute)
    {
//        dd($dispute->messages()->with('user')->get());
        return view('dispute.show', [
            'dispute' => $dispute,
            'messages' => $dispute->messages()->with('user')->get(),
        ]);
    }


    // Reopen the dispute
    public function open(Dispute $dispute)
    {
        $dispute->update(['status' => 'open']);
        return redirect()->route('dashboard', $dispute->id)->with('success', 'Dispute reopened successfully.');

    }

    // Resolve the dispute
    public function resolve(Dispute $dispute)
    {
        $dispute->update(['status' => 'resolved']);
        return redirect()->route('dashboard', $dispute->id)->with('success', 'Dispute resolved');

    }
}
