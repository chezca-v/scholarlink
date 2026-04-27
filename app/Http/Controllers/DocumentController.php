<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use App\Models\Document;
use App\Models\ApplicationDocument;

class DocumentController extends Controller
{
    // Predefined document types
    const DOCUMENT_TYPES = [
        'PSA Birth Certificate',
        'Barangay Certificate of Indigency',
        'Latest Report Card / TOR',
        'SHS Form 138 / Report Card',
        'Certificate of Good Moral Character',
        'Proof of Enrollment / Acceptance Letter',
        '2x2 ID Photo',
        'Income Tax Return / Certificate of Non-Filing',
        'Affidavit of Financial Need',
        'Letter of Recommendation',
        'Other',
    ];

    public function index()
    {
        $documents = Document::query()
            ->where('user_id', Auth::id())
            ->orderBy('document_type')
            ->get();

        return view('applicant.documents.index', [
            'documents'     => $documents,
            'documentTypes' => self::DOCUMENT_TYPES,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type'  => [
                'required',
                'string',
                'in:' . implode(',', self::DOCUMENT_TYPES),
            ],
            'file'           => [
                'required',
                File::types(['pdf', 'jpg', 'jpeg', 'png', 'docx'])->max(5 * 1024),
            ],
            'expiry_date'    => ['nullable', 'date', 'after:today'],
            'application_id' => ['nullable', 'exists:applications,id'],
        ]);

        // Store file per user folder — matches mock data format
        $filePath = $request->file('file')->store(
            'documents/user_' . Auth::id(),
            'public'
        );

        // Create document record
        $document = Document::create([
            'user_id'       => Auth::id(),
            'document_type' => $request->document_type,
            'file_url'      => $filePath,
            'status'        => 'pending',
            'expiry_date'   => $request->expiry_date,
        ]);

        // Link to application if provided
        if ($request->filled('application_id')) {
            ApplicationDocument::create([
                'application_id' => $request->application_id,
                'document_id'    => $document->id,
                'submitted_at'   => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function preview($id)
    {
        $document = Document::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Confirm file exists in storage
        abort_if(!Storage::disk('public')->exists($document->file_url), 404, 'File not found.');

        $filePath = Storage::disk('public')->path($document->file_url);
        $mimeType = mime_content_type($filePath);

        return response()->file($filePath, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($document->file_url) . '"',
        ]);
    }

    // Called by evaluator
    public function verify($id)
    {
        $document = Document::query()->findOrFail($id);

        $document->update([
            'status'      => 'verified',
            'verified_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Document verified.');
    }

    // Called by evaluator
    public function reject(Request $request, $id)
    {
        $document = Document::query()->findOrFail($id);

        $document->update([
            'status' => 'rejected',
        ]);

        return redirect()->back()->with('success', 'Document rejected.');
    }
}