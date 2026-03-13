<?php

namespace App\Http\Controllers;

use App\Models\HelpRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, HelpRequest $helpRequest)
    {
        // Only the student or assigned instructor can comment
        $user    = Auth::user();
        $allowed = $helpRequest->student_id === $user->id
            || $helpRequest->assigned_instructor_id === $user->id;

        abort_if(!$allowed, 403);

        // Don't allow comments on closed requests
        if (in_array($helpRequest->status, ['completed', 'cancelled'])) {
            return response()->json(['error' => 'This request is closed.'], 422);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $comment = $helpRequest->comments()->create([
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        $comment->load('user:id,first_name,last_name,role,profile_photo');

        return response()->json([
            'id'         => $comment->id,
            'message'    => $comment->message,
            'user'       => [
                'id'    => $user->id,
                'name'  => $user->fullName(),
                'role'  => $user->role,
                'is_me' => true,
                'avatar'=> $user->profile_photo ? asset('storage/' . $user->profile_photo) : null,
            ],
            'created_at' => $comment->created_at->diffForHumans(),
            'raw_time'   => $comment->created_at->toISOString(),
        ]);
    }
}
