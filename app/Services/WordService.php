<?php 
// app/Services/WordService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WordService
{
    protected $apiUrl = 'https://api.datamuse.com/words';

    public function getFiveLetterWords()
    {
        $response = Http::get($this->apiUrl, [
            'sp' => '?????' // Pattern for 5-letter words
        ]);

        if ($response->successful()) {
            return array_column($response->json(), 'word');
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
