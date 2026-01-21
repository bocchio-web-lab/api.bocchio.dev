<?php

namespace App\Services\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Cms\Models\Comment;
use Illuminate\Http\Request;

class CommentModerationController extends Controller
{
    /**
     * List all comments (with optional filtering).
     */
    public function index(Request $request)
    {
        $query = Comment::with(['contentItem', 'author'])
            ->orderBy('created_at', 'desc');

        // Filter by approval status
        if ($request->has('approved')) {
            $approved = filter_var($request->approved, FILTER_VALIDATE_BOOLEAN);
            $query->where('approved', $approved);
        }

        $comments = $query->paginate(20);

        return response()->json($comments);
    }

    /**
     * Approve a comment.
     */
    public function approve(Comment $comment)
    {
        $comment->approve();

        return response()->json([
            'data' => $comment->fresh(),
            'message' => 'Comment approved successfully'
        ]);
    }

    /**
     * Reject a comment.
     */
    public function reject(Comment $comment)
    {
        $comment->reject();

        return response()->json([
            'data' => $comment->fresh(),
            'message' => 'Comment rejected successfully'
        ]);
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ], 200);
    }
}
