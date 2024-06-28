<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WordService;
use App\Models\User;

class WoordspelController extends Controller
{
    protected $wordService;

    public function __construct(WordService $wordService)
    {
        $this->wordService = $wordService;
    }

    public function index()
    {
        // Generate a new random word for the new game
        $word = $this->wordService->getRandomFiveLetterWord();
        
        // Store the new word in the session and reset attempts
        session(['word' => $word, 'attempts' => 0]);

        // Return the view with the correct path
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

        if ($win) {
            $user = User::find(auth()->id());
            if ($user) {
                $user->increment('games_won');
            }
        }

        $response = [
            'result' => $result,
            'win' => $win,
            'triesLeft' => $triesLeft,
            'word' => $win || $triesLeft === 0 ? $word : null,
            'row' => $row,
        ];

        return response()->json($response);
    }

    public function resetGame(Request $request)
    {
        // Clear session data for the game
        $request->session()->forget(['word', 'attempts']);

        // Redirect to the play route to start a new game
        return redirect()->route('play');
    }
}
