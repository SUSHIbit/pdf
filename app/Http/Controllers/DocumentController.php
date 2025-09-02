<?php
// app/Http/Controllers/DocumentController.php

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
                    ->max(10 * 1024),
            ],
        ], [
            'document.max' => 'The file size must not exceed 10MB.',
            'document.mimes' => 'Only PDF, DOCX, DOC, TXT, and PPTX files are allowed.',
        ]);

        $user = auth()->user();
        $file = $request->file('document');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('documents', $filename, 'local');

        try {
            $document = Document::create([
                'user_id' => $user->id,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'status' => 'uploaded',
                'question_count' => 10, // Default
                'format' => 'mcq', // Default
            ]);

            $this->extractTextFromDocument($document);

            return redirect()
                ->route('documents.format', $document)
                ->with('success', 'Document uploaded successfully! Choose your study format.');

        } catch (\Exception $e) {
            Log::error('Document upload failed: ' . $e->getMessage());
            
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
            
            return redirect()
                ->back()
                ->with('error', 'Failed to process document. Please try again.');
        }
    }

    public function format(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        if (empty($document->extracted_text)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'No extracted text available for this document.');
        }

        return view('documents.format', compact('document'));
    }

    public function preview(Document $document, Request $request)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        if (empty($document->extracted_text)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'No extracted text available for this document.');
        }

        // Validate and store format
        $format = $request->get('format', 'mcq');
        if (!in_array($format, ['mcq', 'flashcard'])) {
            $format = 'mcq';
        }

        return view('documents.preview', compact('document'))
            ->with('format', $format);
    }

    public function process(Document $document, Request $request)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'question_count' => 'required|integer|in:10,20,30',
            'format' => 'required|string|in:mcq,flashcard',
        ]);

        $user = auth()->user();
        $questionCount = $request->input('question_count');
        $format = $request->input('format');
        $creditCost = $this->getCreditCost($questionCount);
        
        if ($user->credits < $creditCost) {
            return redirect()
                ->route('payment.packages')
                ->with('error', "You need at least {$creditCost} credits to process this document with {$questionCount} " . ($format === 'flashcard' ? 'flashcards' : 'questions') . ".");
        }

        if (empty($document->extracted_text)) {
            return redirect()
                ->route('documents.preview', $document)
                ->with('error', 'No extracted text available for processing.');
        }

        try {
            DB::transaction(function () use ($user, $document, $creditCost, $questionCount, $format) {
                // Update document with selected options
                $document->update([
                    'question_count' => $questionCount,
                    'format' => $format
                ]);
                
                // Deduct credits
                User::where('id', $user->id)->decrement('credits', $creditCost);

                $itemType = $format === 'flashcard' ? 'flashcards' : 'questions';
                CreditTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'usage',
                    'credits' => -$creditCost,
                    'amount' => null,
                    'description' => "Document processing ({$questionCount} {$itemType}): {$document->original_name}",
                ]);
            });

            $this->processDocumentWithAI($document);

            $itemType = $format === 'flashcard' ? 'flashcards' : 'questions';
            return redirect()
                ->route('documents.show', $document)
                ->with('success', "Document processed successfully! Your {$itemType} are ready.");

        } catch (\Exception $e) {
            Log::error('Document AI processing failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Failed to generate content. Please try again.');
        }
    }

    public function show(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        if ($document->format === 'flashcard') {
            return view('documents.flashcards', compact('document'));
        }

        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        return view('documents.edit', compact('document'));
    }

    public function update(Document $document, Request $request)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $document->update([
            'title' => $request->title,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Document title updated successfully.');
    }

    public function destroy(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            // Delete file if exists
            if ($document->file_path && Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }

            // Delete related question set
            if ($document->questionSet) {
                $document->questionSet->delete();
            }

            // Delete document
            $document->delete();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Document deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Document deletion failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Failed to delete document. Please try again.');
        }
    }

    public function download(Document $document)
    {
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$document->questionSet) {
            return redirect()->back()->with('error', 'No content available for download.');
        }

        if ($document->format === 'flashcard') {
            return $this->downloadFlashcards($document);
        }

        return $this->downloadMCQ($document);
    }

    private function downloadMCQ(Document $document)
    {
        $content = "Multiple Choice Questions for: {$document->getDisplayName()}\n";
        $content .= "Questions: {$document->question_count}\n";
        $content .= "Generated on: " . $document->created_at->format('Y-m-d H:i:s') . "\n";
        $content .= str_repeat("=", 60) . "\n\n";

        foreach ($document->questionSet->questions_answers as $index => $qa) {
            $content .= "Question " . ($index + 1) . ": " . $qa['question'] . "\n\n";
            
            if (isset($qa['options']) && is_array($qa['options'])) {
                foreach ($qa['options'] as $optionIndex => $option) {
                    $letter = chr(65 + $optionIndex);
                    $content .= "   {$letter}. {$option}\n";
                }
                
                if (isset($qa['correct_answer'])) {
                    $content .= "\nCorrect Answer: " . $qa['correct_answer'] . "\n";
                }
                
                if (isset($qa['explanation'])) {
                    $content .= "\nExplanation: " . $qa['explanation'] . "\n";
                }
            }
            
            $content .= "\n" . str_repeat("-", 40) . "\n\n";
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="questions_' . $document->question_count . '_' . str_replace(' ', '_', $document->getDisplayName()) . '.txt"');
    }

    private function downloadFlashcards(Document $document)
    {
        $content = "Flashcards for: {$document->getDisplayName()}\n";
        $content .= "Cards: {$document->question_count}\n";
        $content .= "Generated on: " . $document->created_at->format('Y-m-d H:i:s') . "\n";
        $content .= str_repeat("=", 60) . "\n\n";

        foreach ($document->questionSet->questions_answers as $index => $card) {
            $cardNumber = $index + 1;
            
            // Front of card
            $content .= "CARD {$cardNumber} - FRONT\n";
            $content .= str_repeat("-", 20) . "\n";
            $content .= $card['front'] . "\n\n";
            
            // Back of card  
            $content .= "CARD {$cardNumber} - BACK\n";
            $content .= str_repeat("-", 20) . "\n";
            $content .= $card['back'] . "\n";
            
            $content .= "\n" . str_repeat("=", 40) . "\n\n";
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="flashcards_' . $document->question_count . '_' . str_replace(' ', '_', $document->getDisplayName()) . '.txt"');
    }

    private function getCreditCost(int $questionCount): int
    {
        return match($questionCount) {
            10 => 1,
            20 => 2,
            30 => 3,
            default => 1,
        };
    }

    private function extractTextFromDocument(Document $document)
    {
        try {
            $filePath = Storage::path($document->file_path);
            
            if (!file_exists($filePath)) {
                throw new \Exception('File not found: ' . $filePath);
            }

            Log::info('Extracting text from document: ' . $document->original_name);

            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $filePath,
                $document->original_name,
                mime_content_type($filePath),
                null,
                true
            );

            $fileProcessor = app(FileProcessor::class);
            $extractedText = $fileProcessor->extractText($uploadedFile);
            
            if (empty(trim($extractedText))) {
                throw new \Exception('No text could be extracted from the document');
            }

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
            
            $document->update(['status' => 'failed']);
            throw $e;
        }
    }

    private function processDocumentWithAI(Document $document)
    {
        try {
            Log::info('Starting AI processing for document: ' . $document->original_name);
            
            $document->update(['status' => 'processing']);

            $aiService = app(AIService::class);
            
            if ($document->format === 'flashcard') {
                $questionsAnswers = $aiService->generateFlashcards($document->extracted_text, $document->question_count);
            } else {
                $questionsAnswers = $aiService->generateQuestions($document->extracted_text, $document->question_count);
            }

            if (empty($questionsAnswers)) {
                throw new \Exception('No content could be generated from the document');
            }

            QuestionSet::create([
                'document_id' => $document->id,
                'questions_answers' => $questionsAnswers,
            ]);

            $document->update(['status' => 'completed']);

            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }

            Log::info('AI processing completed successfully');

        } catch (\Exception $e) {
            Log::error('AI processing failed: ' . $e->getMessage(), [
                'document_id' => $document->id,
                'error' => $e->getMessage(),
            ]);
            
            $document->update(['status' => 'failed']);
            throw $e;
        }
    }
}