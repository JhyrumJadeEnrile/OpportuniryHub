<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ], [
            'body.required' => 'Please write a comment before posting.',
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $data['body'],
        ]);

        return redirect()->to(route('feed.index').'#post-'.$post->id)->with('status', 'Comment posted.');
    }

    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        abort_unless($comment->user_id === $request->user()->id, 403);

        $postId = $comment->post_id;
        $comment->delete();

        return redirect()->to(route('feed.index').'#post-'.$postId)->with('status', 'Comment deleted.');
    }
}