<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    // Method to show the form to send a friend request
    public function showSendRequestForm($recipientId)
    {
        // Find the recipient user by ID
        $recipient = User::find($recipientId);

        if (!$recipient) {
            return redirect()->back()->with('error', 'Recipient not found.');
        }

        // Pass $recipient to the view
        return view('friends.send-request', compact('recipient'));
    }

    // Method to display incoming friend requests
    public function showIncomingRequests()
{
    // Ensure the user is authenticated
    $user = Auth::user();

    // Fetch incoming friend requests for the authenticated user
    $incomingRequests = $user->receivedFriendRequests()->where('status', 'pending')->get();

    // Pass the $incomingRequests variable to the view
    return view('friends.incoming-requests', compact('incomingRequests'));
}

    // Method to send a friend request
    public function sendFriendRequest(Request $request, $recipientId)
    {
        $user = Auth::user();
        $recipient = User::find($recipientId);

        $existingRequest = Friendship::where('sender_id', $user->id)
                                     ->where('recipient_id', $recipient->id)
                                     ->first();

        if (!$existingRequest) {
            $friendship = new Friendship();
            $friendship->sender_id = $user->id;
            $friendship->recipient_id = $recipient->id;
            $friendship->status = 'pending';
            $friendship->save();

            return redirect()->back()->with('success', 'Friend request sent.');
        } else {
            return redirect()->back()->with('error', 'Friend request already sent.');
        }
    }

    // Method to accept a friend request
    public function acceptFriendRequest(Request $request, $friendshipId)
    {
        $friendship = Friendship::find($friendshipId);

        if ($friendship && $friendship->recipient_id === Auth::id()) {
            $friendship->status = 'accepted';
            $friendship->save();

            return redirect()->back()->with('success', 'Friend request accepted.');
        } else {
            return redirect()->back()->with('error', 'Unable to accept friend request.');
        }
    }

    // Method to decline a friend request
    public function declineFriendRequest(Request $request, $friendshipId)
    {
        $friendship = Friendship::find($friendshipId);

        if ($friendship && $friendship->recipient_id === Auth::id()) {
            $friendship->delete();

            return redirect()->back()->with('success', 'Friend request declined.');
        } else {
            return redirect()->back()->with('error', 'Unable to decline friend request.');
        }
    }
}
