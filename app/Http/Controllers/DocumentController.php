<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\ApplicationDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

class DocumentController extends Controller
{
    public function index() { return view('applicant.documents.index'); }
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|in:transcript,certificate,id_photo,essay,recommendation', // Adjust types as needed
            'file' => [
                'required',
                File::types(['pdf', 'jpg', 'jpeg', 'png'])->max(5 * 1024), // 5MB max
            ],
            'application_id' => 'nullable|exists:applications,id', // Optional link to application
            'expiry_date' => 'nullable|date|after:today',
        ]);

        // Store the file securely
        $filePath = $request->file('file')->store('documents', 'public');

        // Create the document record
        $document = Document::create([
            'user_id' => auth()->id(),
            'document_type' => $request->document_type,
            'file_url' => $filePath,
            'status' => 'pending', // Default status
            'expiry_date' => $request->expiry_date,
        ]);

        // If linked to an application, create the pivot record
        if ($request->application_id) {
            ApplicationDocument::create([
                'application_id' => $request->application_id,
                'document_id' => $document->id,
            ]);
        }

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }
    public function preview($id) { /* Return file stream */ }
    // Admin/Evaluator Actions
    public function verify($id) { /* Mark as authentic */ }
    public function reject(Request $request, $id) { /* Mark as invalid with feedback */ }
}
