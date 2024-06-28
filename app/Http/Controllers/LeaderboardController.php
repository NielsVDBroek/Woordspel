<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboard = User::orderByDesc('games_won')->get(['name', 'games_won']);

        return view('extra.leaderboard', compact('leaderboard'));
    }
}
