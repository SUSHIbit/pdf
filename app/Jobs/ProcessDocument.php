<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\QuestionSet;
use App\Services\AIService;
use App\Services\FileProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProcessDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Document $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle(): void
    {
        try {
            // Update status to processing
            $this->document->update(['status' => 'processing']);

            // Get the file path
            $filePath = Storage::path($this->document->file_path);
            
            if (!file_exists($filePath)) {
                throw new \Exception('File not found: ' . $filePath);
            }

            // Create UploadedFile instance from stored file
            $uploadedFile = new UploadedFile(
                $filePath,
                $this->document->original_name,
                $this->document->file_type,
                null,
                true
            );

            // Extract text
            $fileProcessor = app(FileProcessor::class);
            $extractedText = $fileProcessor->extractText($uploadedFile);
            
            if (empty(trim($extractedText))) {
                throw new \Exception('No text could be extracted from the document');
            }

            $this->document->update(['extracted_text' => $extractedText]);

            // Generate questions
            $aiService = app(AIService::class);
            $questionsAnswers = $aiService->generateQuestions($extractedText);

            if (empty($questionsAnswers)) {
                throw new \Exception('No questions could be generated from the document');
            }

            // Store question set
            QuestionSet::create([
                'document_id' => $this->document->id,
                'questions_answers' => $questionsAnswers,
            ]);

            // Update status to completed
            $this->document->update(['status' => 'completed']);

            // Clean up file after processing
            Storage::delete($this->document->file_path);

        } catch (\Exception $e) {
            Log::error('Document processing failed: ' . $e->getMessage(), [
                'document_id' => $this->document->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->document->update(['status' => 'failed']);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessDocument job failed: ' . $exception->getMessage(), [
            'document_id' => $this->document->id,
        ]);
        
        $this->document->update(['status' => 'failed']);
    }
}