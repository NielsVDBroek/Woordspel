<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WoordspelController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('home.index');
});

Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection is successful.';
    } catch (\Exception $e) {
        return 'Could not connect to the database. Please check your configuration. Error: ' . $e->getMessage();
    }
});

use App\Http\Controllers\LeaderboardController;

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');


use App\Http\Controllers\FriendsController;

Route::middleware(['auth'])->group(function () {
    Route::get('/send-friend-request/{recipientId}', [FriendsController::class, 'showSendRequestForm'])
         ->name('show.send.friend.request.form');
    Route::post('/send-friend-request/{recipientId}', [FriendsController::class, 'sendFriendRequest'])
         ->name('send.friend.request');
         Route::get('/incoming-requests', [FriendsController::class, 'showIncomingRequests'])->name('incoming.requests');
    Route::post('/accept-friend-request/{friendshipId}', [FriendsController::class, 'acceptFriendRequest'])->name('accept.friend.request');
    Route::post('/decline-friend-request/{friendshipId}', [FriendsController::class, 'declineFriendRequest'])->name('decline.friend.request');
});

Route::middleware('auth')->group(function () {
    Route::get('/play', [WoordspelController::class, 'index']);
    Route::post('/guess', [WoordspelController::class, 'checkGuess'])->name('checkGuess');
    Route::post('/reset', [WoordspelController::class, 'resetGame'])->name('resetGame');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
