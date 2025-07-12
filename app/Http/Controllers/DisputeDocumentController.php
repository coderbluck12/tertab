<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\DisputeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DisputeDocumentController extends Controller
{
    /**
     * Store a newly uploaded document for a dispute.
     */
    public function store(Request $request, Dispute $dispute)
    {
        $validator = Validator::make($request->all(), [
            'document' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,txt',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('document');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('dispute_documents', $fileName, 'local');

        $disputeDocument = DisputeDocument::create([
            'dispute_id' => $dispute->id,
            'user_id' => auth()->id(),
            'file_path' => $filePath,
            'original_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    /**
     * Download a dispute document.
     */
    public function download(DisputeDocument $document)
    {
        // Load the dispute with its reference relationship
        $document->load('dispute.reference');
        
        // Get the dispute associated with this document
        $dispute = $document->dispute;
        
        // Check if user has permission to download this document
        // User can download if they are the student or lecturer involved in the dispute
        $user = auth()->user();
        $canDownload = false;
        
        if ($dispute && $dispute->reference) {
            // Check if user is the student who created the dispute
            if ($dispute->reference->student_id == $user->id) {
                $canDownload = true;
            }
            
            // Check if user is the lecturer involved in the dispute
            if ($dispute->reference->lecturer_id == $user->id) {
                $canDownload = true;
            }
            
            // Check if user is an admin
            if ($user->role === 'admin') {
                $canDownload = true;
            }
            
            // Check if user is the one who created the dispute
            if ($dispute->created_by == $user->id) {
                $canDownload = true;
            }
        }
        
        if (!$canDownload) {
            \Log::warning('Unauthorized document download attempt', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'document_id' => $document->id,
                'dispute_id' => $dispute->id ?? null,
                'reference_student_id' => $dispute->reference->student_id ?? null,
                'reference_lecturer_id' => $dispute->reference->lecturer_id ?? null,
                'dispute_created_by' => $dispute->created_by ?? null
            ]);
            abort(403, 'Unauthorized action.');
        }

        if (!Storage::exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::download($document->file_path, $document->original_name);
    }
}
