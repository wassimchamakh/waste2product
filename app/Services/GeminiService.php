<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * GeminiService
 * 
 * Service class for interacting with Google's Gemini AI API.
 * Handles text generation, summarization, and content analysis.
 */
class GeminiService
{
    protected string $apiKey;
    protected string $model;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model');
        $this->apiUrl = config('services.gemini.api_url');
    }

    /**
     * Generate a summary of the given text
     * 
     * @param string $text The text to summarize
     * @param int $maxLength Maximum length of summary
     * @return string|null The generated summary or null on failure
     */
    public function summarize(string $text, int $maxLength = 200): ?string
    {
        $prompt = "Résume ce texte en français en maximum {$maxLength} caractères. Sois concis et informatif:\n\n{$text}";
        
        return $this->generateText($prompt);
    }

    /**
     * Generate suggestions for a comment
     * 
     * @param string $postContent The post content for context
     * @param string $partialComment User's partial comment
     * @return array Array of suggested responses
     */
    public function suggestComments(string $postContent, string $partialComment = ''): array
    {
        $prompt = "Contexte du post: {$postContent}\n\n";
        $prompt .= "Début du commentaire: {$partialComment}\n\n";
        $prompt .= "Suggère 3 réponses constructives et pertinentes en français. Chaque réponse doit être sur une nouvelle ligne et commencer par '-'.";
        
        $response = $this->generateText($prompt);
        
        if (!$response) {
            return [];
        }

        // Parse response into array of suggestions
        $lines = explode("\n", $response);
        $suggestions = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (str_starts_with($line, '-')) {
                $suggestions[] = trim(substr($line, 1));
            }
        }
        
        return array_slice($suggestions, 0, 3);
    }

    /**
     * Check if content is toxic or inappropriate
     * 
     * @param string $content Content to check
     * @return array ['is_toxic' => bool, 'reason' => string|null]
     */
    public function checkToxicity(string $content): array
    {
        $prompt = "Analyse ce texte et détermine s'il contient du contenu toxique, offensant, spam ou inapproprié. Réponds UNIQUEMENT par 'OUI' ou 'NON', suivi d'une brève raison si c'est OUI:\n\n{$content}";
        
        $response = $this->generateText($prompt);
        
        if (!$response) {
            return ['is_toxic' => false, 'reason' => null];
        }

        $isToxic = str_starts_with(strtoupper($response), 'OUI');
        $reason = $isToxic ? substr($response, 3) : null;
        
        return [
            'is_toxic' => $isToxic,
            'reason' => trim($reason),
        ];
    }

    /**
     * Generate tags for a post
     * 
     * @param string $title Post title
     * @param string $body Post body
     * @return array Array of suggested tags
     */
    public function generateTags(string $title, string $body): array
    {
        $prompt = "Titre: {$title}\n\nContenu: " . substr($body, 0, 500) . "\n\n";
        $prompt .= "Génère 5 tags pertinents en français pour ce post (séparés par des virgules, mots simples):";
        
        $response = $this->generateText($prompt);
        
        if (!$response) {
            return [];
        }

        $tags = array_map('trim', explode(',', $response));
        return array_slice($tags, 0, 5);
    }

    /**
     * Core method to generate text using Gemini API
     * 
     * @param string $prompt The prompt to send to AI
     * @return string|null The generated text or null on failure
     */
    protected function generateText(string $prompt): ?string
    {
        if (!$this->apiKey || $this->apiKey === 'your_gemini_api_key_here') {
            Log::warning('Gemini API key not configured');
            return null;
        }

        try {
            // Use v1 API instead of v1beta
            $url = 'https://generativelanguage.googleapis.com/v1/models/' . $this->model . ':generateContent?key=' . $this->apiKey;
            
            $response = Http::timeout(30)
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 500,
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return trim($data['candidates'][0]['content']['parts'][0]['text']);
                }
            }

            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if the service is properly configured
     * 
     * @return bool
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && $this->apiKey !== 'your_gemini_api_key_here';
    }
}
