<?php 

namespace App\Services;

use OpenAI\Client;
use OpenAI\Factory;

class AIService
{
    private Client $client;

    public function __construct()
    {
        $this->client = (new Factory())->withApiKey(config('services.openai.api_key'))->make();
    }

    public function generateQuestions(string $text): array
    {
        // Chunk text if too large (approximate token limit)
        $maxChars = 12000; // ~3000 tokens
        if (strlen($text) > $maxChars) {
            $text = substr($text, 0, $maxChars) . '...';
        }

        $prompt = "Based on the following text, generate exactly 10 questions and answers. Format your response as a JSON array where each item has 'question' and 'answer' keys. Make the questions comprehensive and the answers detailed.\n\nText:\n" . $text;

        $response = $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 2000,
            'temperature' => 0.7,
        ]);

        $content = $response->choices[0]->message->content;
        
        // Try to parse JSON response
        $decoded = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Fallback: try to extract Q&A pairs manually
            return $this->parseQuestionsManually($content);
        }
        
        return $decoded ?: [];
    }

    private function parseQuestionsManually(string $content): array
    {
        // Simple fallback parsing - can be improved
        $questions = [];
        $lines = explode("\n", $content);
        $currentQ = '';
        $currentA = '';
        $isAnswer = false;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if (str_contains($line, '?')) {
                if ($currentQ && $currentA) {
                    $questions[] = ['question' => $currentQ, 'answer' => $currentA];
                }
                $currentQ = $line;
                $currentA = '';
                $isAnswer = true;
            } elseif ($isAnswer) {
                $currentA .= $line . ' ';
            }
        }

        if ($currentQ && $currentA) {
            $questions[] = ['question' => $currentQ, 'answer' => trim($currentA)];
        }

        return array_slice($questions, 0, 10); // Limit to 10
    }
}