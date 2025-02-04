<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Business;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Business $business)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id', // Ensure parent comment exists
        ]);

        // Create a new comment (either regular or reply)
        $comment = new Comment([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'business_id' => $business->id,
            'parent_id' => $validated['parent_id'] ?? null, // Only add parent_id if it's a reply
        ]);

        // Save the comment
        $comment->save();

        return back()->with('success', 'Comment added successfully.');
    }
    public function storeJson(Request $request, Business $business)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id', // Ensure parent comment exists
        ]);

        // Create a new comment (either regular or reply)
        $comment = new Comment([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
            'business_id' => $business->id,
            'parent_id' => $validated['parent_id'] ?? null, // Only add parent_id if it's a reply
        ]);

        // Save the comment
        $comment->save();

        // Load the user relationship
        $comment->load('user');

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully.',
            'comment' => $comment, // Return the created comment for dynamic DOM insertion
        ]);
    }



    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);
        return view('comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update(['content' => $validated['content']]);

        return back()->with('success', 'Comment updated successfully.');
    }
    public function updateJson(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update(['content' => $validated['content']]);

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully'
        ]);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
    public function destroyJson(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.'
        ]);
    }
    public function show(Comment $comment)
    {
        return view('comments.show', compact('comment'));
    }
    public function index()
    {
        $comments = Comment::with('user', 'business', 'replies')->latest()->paginate(10);
        return view('comments.index', compact('comments'));
    }
    public function myComments()
    {
        $comments = auth()->user()->comments()->with('business')->paginate(12);
        return view('comments.my-comments', compact('comments'));
    }

}
