<?php

namespace App\Http\Controllers;

use App\Services\WordService;
use Illuminate\Http\Request;

class WoordspelController extends Controller
{
    protected $wordService;

    public function __construct(WordService $wordService)
    {
        $this->wordService = $wordService;
    }

    public function index()
    {
        $word = $this->wordService->getRandomFiveLetterWord();
        session(['word' => $word, 'attempts' => 0]);
        return view('home.main', ['word' => $word]);
    }

    public function checkGuess(Request $request)
    {
        $guess = strtolower($request->input('guess'));
        $word = strtolower($request->session()->get('word'));
        $row = $request->input('row');

        $result = [];
        for ($i = 0; $i < 5; $i++) {
            if ($guess[$i] === $word[$i]) {
                $result[] = ['letter' => $guess[$i], 'status' => 'green'];
            } elseif (strpos($word, $guess[$i]) !== false) {
                $result[] = ['letter' => $guess[$i], 'status' => 'orange'];
            } else {
                $result[] = ['letter' => $guess[$i], 'status' => 'grey'];
            }
        }

        $win = $guess === $word;
        $attempts = $request->session()->get('attempts', 0) + 1;
        $request->session()->put('attempts', $attempts);
        $triesLeft = 5 - $attempts;

        $response = [
            'result' => $result,
            'win' => $win,
            'triesLeft' => $triesLeft,
            'word' => $win || $triesLeft === 0 ? $word : null,
            'row' => $row,
        ];

        return response()->json($response);
    }

    public function resetGame()
    {
        $word = $this->wordService->getRandomFiveLetterWord();
        session(['word' => $word, 'attempts' => 0]);
        return response()->json(['message' => 'Game reset successfully.', 'word' => $word]);
    }
}
