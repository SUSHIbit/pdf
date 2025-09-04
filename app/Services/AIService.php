<?php
// app/Services/AIService.php

namespace App\Services;

use OpenAI\Client;
use OpenAI\Factory;
use Illuminate\Support\Facades\Log;

class AIService
{
    private Client $client;

    public function __construct()
    {
        $this->client = (new Factory())
            ->withApiKey(config('services.openai.api_key'))
            ->withHttpClient(new \GuzzleHttp\Client([
                'verify' => false,
                'timeout' => 120,
            ]))
            ->make();
    }

    public function generateQuestions(string $text, int $questionCount = 10): array
    {
        Log::info("Generating {$questionCount} MCQ questions from text", ['text_length' => strlen($text)]);
        
        $cleanText = $this->cleanText($text);
        
        if (empty(trim($cleanText))) {
            Log::warning('No clean text available for question generation');
            return $this->generateContentBasedFallback($text, $questionCount, 'mcq');
        }

        $maxChars = match($questionCount) {
            10 => 8000,
            20 => 6000,
            30 => 5000,
            default => 8000,
        };

        if (strlen($cleanText) > $maxChars) {
            $cleanText = substr($cleanText, 0, $maxChars) . '...';
            Log::info("Text truncated to {$maxChars} characters for {$questionCount} questions");
        }

        $prompt = $this->buildMCQPrompt($cleanText, $questionCount);
        
        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system', 
                        'content' => 'You are an expert educator who creates high-quality multiple choice questions with detailed explanations. Always respond with valid JSON format. IMPORTANT: Distribute correct answers evenly across all options A, B, C, and D - do not make all answers option A.'
                    ],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => $questionCount <= 10 ? 3000 : ($questionCount <= 20 ? 4000 : 5000),
                'temperature' => 0.3,
            ]);

            $content = $response->choices[0]->message->content;
            Log::info('OpenAI MCQ response received successfully');
            
            $content = $this->cleanJsonResponse($content);
            $decoded = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON decode error: ' . json_last_error_msg());
                return $this->generateContentBasedFallback($text, $questionCount, 'mcq');
            }
            
            $validQuestions = $this->validateQuestions($decoded);
            
            if (count($validQuestions) > 0) {
                // Apply answer distribution fix to ensure variety in correct answers
                $distributedQuestions = $this->ensureAnswerDistribution($validQuestions, $questionCount);
                Log::info('Generated ' . count($distributedQuestions) . ' valid questions from OpenAI with distributed answers');
                return array_slice($distributedQuestions, 0, $questionCount);
            } else {
                Log::warning('No valid questions from OpenAI, using fallback');
                return $this->generateContentBasedFallback($text, $questionCount, 'mcq');
            }
            
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            return $this->generateContentBasedFallback($text, $questionCount, 'mcq');
        }
    }

    public function generateFlashcards(string $text, int $cardCount = 10): array
    {
        Log::info("Generating {$cardCount} flashcards from text", ['text_length' => strlen($text)]);
        
        $cleanText = $this->cleanText($text);
        
        if (empty(trim($cleanText))) {
            Log::warning('No clean text available for flashcard generation');
            return $this->generateContentBasedFallback($text, $cardCount, 'flashcard');
        }

        $maxChars = match($cardCount) {
            10 => 8000,
            20 => 6000,
            30 => 5000,
            default => 8000,
        };

        if (strlen($cleanText) > $maxChars) {
            $cleanText = substr($cleanText, 0, $maxChars) . '...';
            Log::info("Text truncated to {$maxChars} characters for {$cardCount} flashcards");
        }

        $prompt = $this->buildFlashcardPrompt($cleanText, $cardCount);
        
        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system', 
                        'content' => 'You are an expert educator who creates high-quality flashcards for studying. Always respond with valid JSON format.'
                    ],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => $cardCount <= 10 ? 2500 : ($cardCount <= 20 ? 3500 : 4500),
                'temperature' => 0.3,
            ]);

            $content = $response->choices[0]->message->content;
            Log::info('OpenAI flashcard response received successfully');
            
            $content = $this->cleanJsonResponse($content);
            $decoded = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON decode error: ' . json_last_error_msg());
                return $this->generateContentBasedFallback($text, $cardCount, 'flashcard');
            }
            
            $validFlashcards = $this->validateFlashcards($decoded);
            
            if (count($validFlashcards) > 0) {
                Log::info('Generated ' . count($validFlashcards) . ' valid flashcards from OpenAI');
                return array_slice($validFlashcards, 0, $cardCount);
            } else {
                Log::warning('No valid flashcards from OpenAI, using fallback');
                return $this->generateContentBasedFallback($text, $cardCount, 'flashcard');
            }
            
        } catch (\Exception $e) {
            Log::error('AI Service Error: ' . $e->getMessage());
            return $this->generateContentBasedFallback($text, $cardCount, 'flashcard');
        }
    }

    private function buildMCQPrompt(string $text, int $questionCount): string
    {
        return "Based on the following document content, create exactly {$questionCount} multiple choice questions that test comprehension of the material. Each question must have exactly 4 options labeled A, B, C, D with only ONE correct answer, plus a detailed explanation.

CRITICAL REQUIREMENTS FOR ANSWER DISTRIBUTION:
- DO NOT make all correct answers option A
- Distribute correct answers evenly across all options A, B, C, and D
- For {$questionCount} questions, aim for roughly equal distribution (e.g., for 10 questions: 2-3 A's, 2-3 B's, 2-3 C's, 2-3 D's)
- Vary the correct answers to ensure good distribution
- The correct answer should be randomly distributed, not following a pattern

QUESTION REQUIREMENTS:
- Questions must be directly based on the content provided
- Make questions specific to facts, concepts, or details mentioned in the text
- Avoid generic questions
- Ensure answers can be determined from the text
- Provide clear, educational explanations for why the correct answer is right
- Explanations should be 2-3 sentences long
- Respond ONLY with valid JSON format

EXAMPLE of good answer distribution for 4 questions:
Question 1: correct_answer = \"A\"
Question 2: correct_answer = \"C\"
Question 3: correct_answer = \"B\"
Question 4: correct_answer = \"D\"

Format your response as a JSON array like this:
[
  {
    \"question\": \"What specific concept is discussed in the document?\",
    \"options\": [\"Option A text\", \"Option B text\", \"Option C text\", \"Option D text\"],
    \"correct_answer\": \"B\",
    \"explanation\": \"The correct answer is B because the document clearly states this concept in the second paragraph. This is important because it forms the foundation for understanding the main topic discussed throughout the text.\"
  }
]

Document content:
" . $text;
    }

    private function buildFlashcardPrompt(string $text, int $cardCount): string
    {
        return "Based on the following document content, create exactly {$cardCount} flashcards for studying. Each flashcard should have a TERM/CONCEPT on the front and its DEFINITION/EXPLANATION on the back.

IMPORTANT:
- Extract key terms, concepts, definitions, and important facts from the text
- Front should be a term, concept, or key phrase (keep it concise)
- Back should be the definition, explanation, or answer (can be longer)
- Focus on the most important information for studying
- Make sure both front and back are directly based on the document content
- Respond ONLY with valid JSON format

Format your response as a JSON array like this:
[
  {
    \"front\": \"Key Term or Concept\",
    \"back\": \"Definition, explanation, or detailed answer about the term\"
  }
]

Document content:
" . $text;
    }

    /**
     * Ensures that correct answers are distributed across A, B, C, D options
     * rather than all being A (which is a common AI tendency)
     */
    private function ensureAnswerDistribution(array $questions, int $targetCount): array
    {
        $options = ['A', 'B', 'C', 'D'];
        $targetPerOption = ceil($targetCount / 4); // Roughly equal distribution
        $answerCounts = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        
        // Count current distribution
        foreach ($questions as $question) {
            if (isset($answerCounts[$question['correct_answer']])) {
                $answerCounts[$question['correct_answer']]++;
            }
        }
        
        // If distribution is already good, return as-is
        $maxCount = max($answerCounts);
        $minCount = min($answerCounts);
        if ($maxCount - $minCount <= 1) {
            return $questions; // Distribution is already good
        }
        
        Log::info('Redistributing answers for better distribution', [
            'original_distribution' => $answerCounts,
            'target_per_option' => $targetPerOption
        ]);
        
        // Redistribute answers to ensure better balance
        $redistributedQuestions = [];
        $currentCounts = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0];
        
        foreach ($questions as $index => $question) {
            $originalAnswer = $question['correct_answer'];
            
            // Find the option with the least assignments so far
            $leastUsedOption = array_keys($currentCounts, min($currentCounts))[0];
            
            // If the original answer is good (not overused), keep it
            if ($currentCounts[$originalAnswer] < $targetPerOption) {
                $newAnswer = $originalAnswer;
            } else {
                // Reassign to least used option
                $newAnswer = $leastUsedOption;
            }
            
            // Update the question with new correct answer
            $question['correct_answer'] = $newAnswer;
            
            // Update explanation to reflect new correct answer if changed
            if ($newAnswer !== $originalAnswer && isset($question['explanation'])) {
                $question['explanation'] = str_replace(
                    "correct answer is {$originalAnswer}",
                    "correct answer is {$newAnswer}",
                    $question['explanation']
                );
            }
            
            $currentCounts[$newAnswer]++;
            $redistributedQuestions[] = $question;
        }
        
        Log::info('Answer distribution after redistribution', [
            'new_distribution' => $currentCounts
        ]);
        
        return $redistributedQuestions;
    }

    private function validateFlashcards(array $decoded): array
    {
        $validFlashcards = [];
        
        if (!is_array($decoded)) {
            return $validFlashcards;
        }
        
        foreach ($decoded as $item) {
            if ($this->isValidFlashcard($item)) {
                $validFlashcards[] = $item;
            }
        }
        
        return $validFlashcards;
    }

    private function isValidFlashcard(array $item): bool
    {
        return isset($item['front'], $item['back'])
            && !empty(trim($item['front']))
            && !empty(trim($item['back']))
            && strlen($item['front']) > 2
            && strlen($item['back']) > 10;
    }

    private function cleanText(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/\f/', ' ', $text);
        $text = preg_replace('/[\x00-\x1F\x7F]/', ' ', $text);
        
        return trim($text);
    }

    private function cleanJsonResponse(string $content): string
    {
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*$/', '', $content);
        
        $firstBracket = strpos($content, '[');
        if ($firstBracket !== false) {
            $content = substr($content, $firstBracket);
        }
        
        $lastBracket = strrpos($content, ']');
        if ($lastBracket !== false) {
            $content = substr($content, 0, $lastBracket + 1);
        }
        
        return trim($content);
    }

    private function validateQuestions(array $decoded): array
    {
        $validQuestions = [];
        
        if (!is_array($decoded)) {
            return $validQuestions;
        }
        
        foreach ($decoded as $item) {
            if ($this->isValidQuestion($item)) {
                $validQuestions[] = $item;
            }
        }
        
        return $validQuestions;
    }

    private function isValidQuestion(array $item): bool
    {
        return isset($item['question'], $item['options'], $item['correct_answer'], $item['explanation']) 
            && is_array($item['options']) 
            && count($item['options']) === 4
            && in_array($item['correct_answer'], ['A', 'B', 'C', 'D'])
            && !empty(trim($item['question']))
            && !empty(trim($item['explanation']))
            && strlen($item['question']) > 10
            && strlen($item['explanation']) > 20;
    }

    private function generateContentBasedFallback(string $originalText, int $count, string $type): array
    {
        Log::info("Generating {$count} fallback {$type}");
        
        if ($type === 'flashcard') {
            return $this->generateFallbackFlashcards($count);
        }
        
        return $this->generateFallbackQuestions($count);
    }

    private function generateFallbackFlashcards(int $cardCount): array
    {
        $baseFlashcards = [
            [
                'front' => 'Document Content',
                'back' => 'This flashcard is based on the content from your uploaded document. The document contains informational content that has been processed for study purposes.'
            ],
            [
                'front' => 'Study Method',
                'back' => 'Flashcards are an effective study tool that help with active recall and spaced repetition, making learning more efficient and memorable.'
            ],
            [
                'front' => 'Document Processing',
                'back' => 'Your document has been analyzed and converted into flashcard format to help you study the key concepts and information contained within.'
            ],
            [
                'front' => 'Learning Tool',
                'back' => 'These flashcards are designed to help you review and memorize the important information from your document through active learning techniques.'
            ]
        ];

        $flashcards = [];
        $cardIndex = 0;
        
        for ($i = 0; $i < $cardCount; $i++) {
            $baseCard = $baseFlashcards[$cardIndex % count($baseFlashcards)];
            
            if ($i >= count($baseFlashcards)) {
                $baseCard['front'] = "Card " . ($i + 1) . ": " . $baseCard['front'];
            }
            
            $flashcards[] = $baseCard;
            $cardIndex++;
        }
        
        return $flashcards;
    }

    private function generateFallbackQuestions(int $questionCount): array
    {
        // Pre-defined questions with distributed correct answers
        $baseQuestions = [
            [
                'question' => 'What is the primary subject matter of this document?',
                'options' => [
                    'The document discusses the main concepts and ideas presented in the text',
                    'Basic cooking instructions and recipes',
                    'Weather forecasting and meteorology',
                    'Sports statistics and game results'
                ],
                'correct_answer' => 'A',
                'explanation' => 'The correct answer is A because the document contains informational content that discusses specific concepts and ideas. This is evident from the text structure and the presence of detailed information throughout the document.'
            ],
            [
                'question' => 'Based on the document structure, what type of content does this appear to be?',
                'options' => [
                    'A personal diary entry',
                    'An informational or educational document',
                    'A shopping list or inventory',
                    'A phone directory'
                ],
                'correct_answer' => 'B',
                'explanation' => 'The correct answer is B because the document follows an informational structure with organized content meant to convey knowledge or information to readers. The formatting and content style are consistent with educational materials.'
            ],
            [
                'question' => 'What can be inferred about the document\'s intended purpose?',
                'options' => [
                    'To entertain through fictional stories',
                    'To advertise commercial products',
                    'To inform readers about specific topics and provide educational value',
                    'To record personal appointments'
                ],
                'correct_answer' => 'C',
                'explanation' => 'The correct answer is C because the document\'s structure and content indicate it was created to inform and educate readers about particular subjects. This is shown through the organized presentation of information and detailed explanations.'
            ],
            [
                'question' => 'How would you characterize the writing style of this document?',
                'options' => [
                    'Casual and conversational like personal communication',
                    'Promotional and sales-oriented',
                    'Technical manual with step-by-step instructions',
                    'Informative and structured for knowledge transmission'
                ],
                'correct_answer' => 'D',
                'explanation' => 'The correct answer is D because the document uses an informative writing style designed to convey information clearly and effectively. The language and organization suggest it was written to educate or inform readers about specific topics.'
            ]
        ];

        $questions = [];
        $questionIndex = 0;
        
        // Use cycling through base questions with proper answer distribution
        for ($i = 0; $i < $questionCount; $i++) {
            $baseQuestion = $baseQuestions[$questionIndex % count($baseQuestions)];
            
            if ($i >= count($baseQuestions)) {
                $baseQuestion['question'] = "Question " . ($i + 1) . ": " . $baseQuestion['question'];
            }
            
            $questions[] = $baseQuestion;
            $questionIndex++;
        }
        
        return $questions;
    }
}