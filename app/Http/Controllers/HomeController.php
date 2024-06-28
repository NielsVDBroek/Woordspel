<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch users sorted by games won in descending order
        $users = User::orderBy('games_won', 'desc')->get(['name', 'games_won']);
        
        return view('home.index', compact('users'));
    }
}
