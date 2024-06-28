<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WordService
{
    protected $apiUrl = 'https://api.datamuse.com/words?sp=?????';

    public function getFiveLetterWords()
    {
        try {
            $response = Http::get($this->apiUrl);

            if ($response->successful()) {
                $words = array_column($response->json(), 'word');
                // Filter out words that are not exactly five letters long or contain spaces
                $filteredWords = array_filter($words, function($word) {
                    return strlen($word) === 5 && strpos($word, ' ') === false;
                });
                return $filteredWords;
            }

            Log::error('Failed to fetch words from API.', ['status' => $response->status()]);
        } catch (\Exception $e) {
            Log::error('Exception occurred while fetching words from API.', ['exception' => $e->getMessage()]);
        }

        return [];
    }

    public function getRandomFiveLetterWord()
    {
        $words = $this->getFiveLetterWords();
        if (!empty($words)) {
            return $words[array_rand($words)];
        }
        return null;
    }
}
