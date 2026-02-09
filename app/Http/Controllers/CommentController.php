<?php

namespace App\Http\Controllers;


use App\Models\HelpRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;
    public function store(Request $request, HelpRequest $helpRequest)
    {
        $this->authorize('view', $helpRequest);

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'is_internal' => 'nullable|boolean',
        ]);

        $comment = $helpRequest->comments()->create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'is_internal' => $validated['is_internal'] ?? false,
        ]);

        // Log activity
        $helpRequest->activityLogs()->create([
            'user_id' => Auth::id(),
            'action' => 'comment_added',
            'description' => 'New comment added',
        ]);

        return back()->with('success', 'Comment added successfully!');
    }
}

