<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SentimentAnalysisService
{
    /**
     * Python API base URL
     */
    private string $apiUrl;

    /**
     * Request timeout in seconds
     */
    private int $timeout;

    public function __construct()
    {
        $this->apiUrl = config('services.sentiment_api.url', 'http://127.0.0.1:5000');
        $this->timeout = config('services.sentiment_api.timeout', 10);
    }

    /**
     * Check if sentiment API is available
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout(2)->get($this->apiUrl . '/health');
            return $response->successful() && $response->json('status') === 'healthy';
        } catch (\Exception $e) {
            Log::warning('Sentiment API health check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Analyze single text for sentiment
     *
     * @param string $text
     * @return array|null
     */
    public function analyze(string $text): ?array
    {
        if (empty(trim($text))) {
            return null;
        }

        // Check cache first (cache for 1 hour)
        $cacheKey = 'sentiment_' . md5($text);
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $response = Http::timeout($this->timeout)
                ->post($this->apiUrl . '/analyze', [
                    'text' => $text
                ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['success']) && $result['success']) {
                    $data = [
                        'sentiment' => $result['sentiment'] ?? null,
                        'themes' => $result['themes'] ?? []
                    ];
                    
                    // Cache the result
                    Cache::put($cacheKey, $data, now()->addHour());
                    
                    return $data;
                }
            }

            Log::error('Sentiment API analyze failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Sentiment analysis exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Analyze multiple feedback entries in batch
     *
     * @param array $feedbackList Array of ['id' => int, 'text' => string]
     * @return array|null
     */
    public function analyzeBatch(array $feedbackList): ?array
    {
        if (empty($feedbackList)) {
            return null;
        }

        try {
            $response = Http::timeout($this->timeout * 2) // Double timeout for batch
                ->post($this->apiUrl . '/analyze-batch', [
                    'feedback' => $feedbackList
                ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['success']) && $result['success']) {
                    return $result['data'] ?? null;
                }
            }

            Log::error('Sentiment API batch analyze failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Sentiment batch analysis exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Extract themes from text
     *
     * @param string $text
     * @param int $maxThemes
     * @return array
     */
    public function extractThemes(string $text, int $maxThemes = 5): array
    {
        if (empty(trim($text))) {
            return [];
        }

        try {
            $response = Http::timeout($this->timeout)
                ->post($this->apiUrl . '/themes', [
                    'text' => $text,
                    'max_themes' => $maxThemes
                ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['success']) && $result['success']) {
                    return $result['themes'] ?? [];
                }
            }

            return [];

        } catch (\Exception $e) {
            Log::error('Theme extraction exception: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get sentiment label color for UI display
     *
     * @param string $label
     * @return string
     */
    public static function getSentimentColor(string $label): string
    {
        return match(strtolower($label)) {
            'positive' => 'success',
            'negative' => 'error',
            'neutral' => 'warning',
            default => 'gray'
        };
    }

    /**
     * Get sentiment icon
     *
     * @param string $label
     * @return string
     */
    public static function getSentimentIcon(string $label): string
    {
        return match(strtolower($label)) {
            'positive' => '😊',
            'negative' => '😞',
            'neutral' => '😐',
            default => '❓'
        };
    }

    /**
     * Format sentiment score for display
     *
     * @param float $score
     * @return string
     */
    public static function formatScore(float $score): string
    {
        return number_format($score * 100, 1) . '%';
    }
}
