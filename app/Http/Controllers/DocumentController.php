<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Models\CreditTransaction;
use App\Models\QuestionSet;
use App\Services\AIService;
use App\Services\FileProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                'file',
                File::types(['pdf', 'docx', 'doc', 'txt', 'pptx'])
                    ->max(10 * 1024), // 10MB in KB
            ],
        ], [
            'document.max' => 'The file size must not exceed 10MB.',
            'document.mimes' => 'Only PDF, DOCX, DOC, TXT, and PPTX files are allowed.',
        ]);

        $user = auth()->user();
        
        // Check if user has credits
        if ($user->credits < 1) {
            return redirect()
                ->route('payment.packages')
                ->with('error', 'You need at least 1 credit to process a document.');
        }

        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents', $filename, 'local');

        try {
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

            // Extract text immediately
            $this->extractTextFromDocument($document);

            // Redirect to preview page
            return redirect()
                ->route('documents.preview', $document)
                ->with('success', 'Document uploaded successfully! Review the extracted content below.');

        } catch (\Exception $e) {
            Log::error('Document upload failed: ' . $e->getMessage());
            
            // Clean up uploaded file on error
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
            
            return redirect()
                ->back()
                ->with('error', 'Failed to process document. Please try again.');
        }
    }

    private function extractTextFromDocument(Document $document)
    {
        try {
            // Get the file path
            $filePath = Storage::path($document->file_path);
            
            if (!file_exists($filePath)) {
                throw new \Exception('File not found: ' . $filePath);
            }

            Log::info('Extracting text from document: ' . $document->original_name);

            // Create a temporary UploadedFile instance for processing
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $filePath,
                $document->original_name,
                mime_content_type($filePath),
                null,
                true
            );

            // Extract text
            $fileProcessor = app(FileProcessor::class);
            $extractedText = $fileProcessor->extractText($uploadedFile);
            
            Log::info('Extracted text length: ' . strlen($extractedText));
            
            if (empty(trim($extractedText))) {
                throw new \Exception('No text could be extracted from the document');
            }

            // Update document with extracted text
            $document->update([
                'extracted_text' => $extractedText,
                'status' => 'text_extracted'
            ]);

            Log::info('Text extraction completed successfully');

        } catch (\Exception $e) {
            Log::error('Text extraction failed: ' . $e->getMessage(), [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);
            
            // Update status to failed
            $document->update(['status' => 'failed']);
            
            throw $e;
        }
    }

    public function preview(Document $document)
    {
        // Check ownership
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if text has been extracted
        if (empty($document->extracted_text)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'No extracted text available for this document.');
        }

        return view('documents.preview', compact('document'));
    }

    public function process(Document $document)
    {
        // Check ownership
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $user = auth()->user();
        
        // Check if user has credits
        if ($user->credits < 1) {
            return redirect()
                ->route('payment.packages')
                ->with('error', 'You need at least 1 credit to process a document.');
        }

        // Check if document has extracted text
        if (empty($document->extracted_text)) {
            return redirect()
                ->route('documents.preview', $document)
                ->with('error', 'No extracted text available for processing.');
        }

        try {
            // Deduct credit and log transaction
            DB::transaction(function () use ($user, $document) {
                // Deduct credit
                User::where('id', $user->id)->decrement('credits', 1);

                // Log credit transaction
                CreditTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'usage',
                    'credits' => -1,
                    'amount' => null,
                    'description' => "Document processing: {$document->original_name}",
                ]);
            });

            // Process with AI
            $this->processDocumentWithAI($document);

            // Redirect to quiz page
            return redirect()
                ->route('documents.show', $document)
                ->with('success', 'Document processed successfully! Your questions are ready.');

        } catch (\Exception $e) {
            Log::error('Document AI processing failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Failed to generate questions. Please try again.');
        }
    }

    private function processDocumentWithAI(Document $document)
    {
        try {
            Log::info('Starting AI processing for document: ' . $document->original_name);
            
            // Update status to processing
            $document->update(['status' => 'processing']);

            // Generate questions using AI
            $aiService = app(AIService::class);
            $questionsAnswers = $aiService->generateQuestions($document->extracted_text);

            Log::info('Generated questions count: ' . count($questionsAnswers));

            if (empty($questionsAnswers)) {
                throw new \Exception('No questions could be generated from the document');
            }

            // Store question set
            QuestionSet::create([
                'document_id' => $document->id,
                'questions_answers' => $questionsAnswers,
            ]);

            // Update status to completed
            $document->update(['status' => 'completed']);

            // Clean up file after processing
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }

            Log::info('AI processing completed successfully');

        } catch (\Exception $e) {
            Log::error('AI processing failed: ' . $e->getMessage(), [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);
            
            // Update status to failed
            $document->update(['status' => 'failed']);
            
            throw $e;
        }
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

        $content = "Multiple Choice Questions for: {$document->original_name}\n";
        $content .= "Generated on: " . $document->created_at->format('Y-m-d H:i:s') . "\n";
        $content .= str_repeat("=", 60) . "\n\n";

        foreach ($document->questionSet->questions_answers as $index => $qa) {
            $content .= "Question " . ($index + 1) . ": " . $qa['question'] . "\n\n";
            
            if (isset($qa['options']) && is_array($qa['options'])) {
                // Multiple choice format
                foreach ($qa['options'] as $optionIndex => $option) {
                    $letter = chr(65 + $optionIndex); // A, B, C, D
                    $content .= "   {$letter}. {$option}\n";
                }
                
                if (isset($qa['correct_answer'])) {
                    $content .= "\nCorrect Answer: " . $qa['correct_answer'] . "\n";
                }
            } else {
                // Fallback for old format
                $content .= "Answer: " . ($qa['answer'] ?? 'No answer available') . "\n";
            }
            
            $content .= "\n" . str_repeat("-", 40) . "\n\n";
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="questions_' . $document->filename . '.txt"');
    }
}