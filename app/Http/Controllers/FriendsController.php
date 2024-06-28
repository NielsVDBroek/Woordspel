<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    // Method to send a friend request
    public function sendFriendRequest(Request $request, $recipientId)
    {
        $user = Auth::user(); // Get the authenticated user
        $recipient = User::find($recipientId); // Find the recipient user

        // Check if a friendship request already exists
        $existingRequest = Friendship::where('sender_id', $user->id)
                                     ->where('recipient_id', $recipient->id)
                                     ->first();

        if (!$existingRequest) {
            // Create a new friendship request
            $friendship = new Friendship();
            $friendship->sender_id = $user->id;
            $friendship->recipient_id = $recipient->id;
            $friendship->status = 'pending'; // or any initial status
            $friendship->save();

            // Optionally, you can send a notification to $recipient here

            return redirect()->back()->with('success', 'Friend request sent.');
        } else {
            return redirect()->back()->with('error', 'Friend request already sent.');
        }
    }

    // Method to accept a friend request
    public function acceptFriendRequest(Request $request, $friendshipId)
    {
        $friendship = Friendship::find($friendshipId); // Find the friendship request

        // Check if the authenticated user is the recipient of the request
        if ($friendship && $friendship->recipient_id === Auth::id()) {
            // Accepting a friend request
            $friendship->status = 'accepted';
            $friendship->save();

            // Optionally, you can notify the sender here

            return redirect()->back()->with('success', 'Friend request accepted.');
        } else {
            return redirect()->back()->with('error', 'Unable to accept friend request.');
        }
    }

    // Method to decline a friend request
    public function declineFriendRequest(Request $request, $friendshipId)
    {
        $friendship = Friendship::find($friendshipId); // Find the friendship request

        // Check if the authenticated user is the recipient of the request
        if ($friendship && $friendship->recipient_id === Auth::id()) {
            // Declining a friend request
            $friendship->delete(); // or update status to 'declined' as per your application logic

            return redirect()->back()->with('success', 'Friend request declined.');
        } else {
            return redirect()->back()->with('error', 'Unable to decline friend request.');
        }
    }

    // Method to show incoming friend requests
    public function showIncomingRequests()
    {
        $user = Auth::user(); // Get the authenticated user
        $incomingRequests = $user->receivedFriendRequests()->where('status', 'pending')->get();

        return view('incoming-requests', compact('incomingRequests'));
    }
}
