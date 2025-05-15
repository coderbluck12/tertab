<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use Illuminate\Http\Request;

class DisputeMessageController extends Controller
{
    public function store(Request $request, Dispute $dispute)
    {
        $request->validate(['message' => 'required|string']);

        $message = $dispute->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

//        return response()->json(['message' => 'Message sent', 'data' => $message]);
        return redirect()->route('disputes.show', $dispute->id)->with('success', 'Message sent.');
    }
}
