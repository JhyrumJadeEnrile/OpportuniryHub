<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Vote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     * Up/down vote a post. Pressing the same arrow again removes the vote.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'value' => ['required', 'in:1,-1'],
        ]);

        $value = (int) $data['value'];
        $userId = $request->user()->id;

        $vote = Vote::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($vote && $vote->value === $value) {
            $vote->delete();                       // same arrow again: remove vote
        } elseif ($vote) {
            $vote->update(['value' => $value]);    // switch up <-> down
        } else {
            Vote::create([
                'post_id' => $post->id,
                'user_id' => $userId,
                'value'   => $value,
            ]);
        }

        return redirect()->to(route('feed.index').'#post-'.$post->id);
    }
}