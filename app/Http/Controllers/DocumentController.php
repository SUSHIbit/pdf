<?php
// app/Http/Controllers/DocumentController.php

namespace App\Http\Controllers;

use App\Jobs\ProcessDocument;
use App\Models\Document;
use App\Models\User;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\File;

class DocumentController extends Controller
{
    public function upload()
    {
        return view('documents.upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'document' => [
                'required',
                File::types(['pdf', 'docx', 'doc', 'txt'])
                    ->max(10 * 1024), // 10MB
            ],
        ]);

        $user = auth()->user();
        
        // Check if user has credits
        if ($user->credits < 1) {
            return redirect()
                ->route('payment.packages')
                ->with('error', 'You need at least 1 credit to process a document.');
        }

        // Store file
        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents', $filename, 'local');

        // Use database transaction for credit usage
        DB::transaction(function () use ($user, $file, $filename, $path) {
            // Create document record
            $document = Document::create([
                'user_id' => $user->id,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'status' => 'uploaded',
            ]);

            // Deduct credit directly using DB
            User::where('id', $user->id)->decrement('credits', 1);

            // Log credit transaction
            CreditTransaction::create([
                'user_id' => $user->id,
                'type' => 'usage',
                'credits' => -1,
                'amount' => null,
                'description' => "Document processing: {$file->getClientOriginalName()}",
            ]);

            // Dispatch processing job
            ProcessDocument::dispatch($document);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Document uploaded successfully! Processing will begin shortly.');
    }

    public function show(Document $document)
    {
        // Check ownership
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        return view('documents.show', compact('document'));
    }

    public function download(Document $document)
    {
        // Check ownership
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$document->questionSet) {
            return redirect()->back()->with('error', 'No questions available for download.');
        }

        $content = "Questions and Answers for: {$document->original_name}\n";
        $content .= "Generated on: " . $document->created_at->format('Y-m-d H:i:s') . "\n\n";

        foreach ($document->questionSet->questions_answers as $index => $qa) {
            $content .= "Q" . ($index + 1) . ": " . $qa['question'] . "\n";
            $content .= "A" . ($index + 1) . ": " . $qa['answer'] . "\n\n";
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="questions_' . $document->filename . '.txt"');
    }
}