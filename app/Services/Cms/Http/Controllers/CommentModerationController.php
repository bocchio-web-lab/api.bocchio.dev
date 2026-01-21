<?php

namespace App\Services\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Cms\Models\Comment;
use Illuminate\Http\Request;

/**
 * @group CMS - Management
 *
 * Moderate comments on content items. Comments can be approved, rejected, or deleted.
 * All comment moderation requires tenant membership.
 */
class CommentModerationController extends Controller
{
    /**
     * List comments
     *
     * Returns a paginated list of comments with optional filtering by approval status.
     * Includes related content item and author information.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @queryParam approved boolean Filter by approval status. Example: false
     *
     * @response 200 {
     *   "current_page": 1,
     *   "data": [
     *     {
     *       "id": 1,
     *       "content_item_id": 1,
     *       "author_id": 2,
     *       "body": "Great article!",
     *       "approved": true,
     *       "created_at": "2025-01-22T10:00:00.000000Z",
     *       "updated_at": "2025-01-22T10:00:00.000000Z",
     *       "content_item": {
     *         "id": 1,
     *         "title": "My First Blog Post",
     *         "slug": "my-first-blog-post"
     *       },
     *       "author": {
     *         "id": 2,
     *         "name": "Jane Smith",
     *         "email": "jane@example.com"
     *       }
     *     }
     *   ],
     *   "per_page": 20,
     *   "total": 45
     * }
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
     * Approve comment
     *
     * Approves a comment, making it visible on the public content API.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @urlParam comment integer required The ID of the comment. Example: 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "approved": true,
     *     "updated_at": "2025-01-22T11:00:00.000000Z"
     *   },
     *   "message": "Comment approved successfully"
     * }
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
     * Reject comment
     *
     * Rejects a comment, hiding it from the public content API.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @urlParam comment integer required The ID of the comment. Example: 1
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "approved": false,
     *     "updated_at": "2025-01-22T11:00:00.000000Z"
     *   },
     *   "message": "Comment rejected successfully"
     * }
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
     * Delete comment
     *
     * Permanently deletes a comment. This action cannot be undone.
     *
     * @authenticated
     * @header X-Tenant-ID required The ID of the tenant context. Example: 1
     *
     * @urlParam comment integer required The ID of the comment. Example: 1
     *
     * @response 200 {
     *   "message": "Comment deleted successfully"
     * }
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ], 200);
    }
}
