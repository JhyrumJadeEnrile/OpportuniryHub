<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::with([
                'user:id,name,member_type,batch_year,program',
                'comments.user:id,name',
                'votes' => fn ($q) => $q->where('user_id', auth()->id()),
            ])
            ->withCount('comments')
            ->withSum('votes as score', 'value')
            ->latest()
            ->paginate(10);

        return view('feed.index', [
            'posts'      => $posts,
            'categories' => Post::CATEGORIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category'   => ['required', 'in:'.implode(',', array_keys(Post::CATEGORIES))],
            'body'       => ['required', 'string', 'max:1000'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png'],
        ], [
            'body.required'       => 'Please write a message before publishing.',
            'attachment.max'      => 'The file must be 5 MB or smaller.',
            'attachment.mimes'    => 'Allowed files: PDF, Word, PowerPoint, Excel, JPG or PNG.',
        ]);

        $post = new Post([
            'category' => $data['category'],
            'body'     => $data['body'],
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $post->attachment_path = $file->store('attachments', 'public');
            $post->attachment_name = $file->getClientOriginalName();
        }

        $request->user()->posts()->save($post);

        return redirect()->route('feed.index')->with('status', 'Posted to the newsfeed.');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        if ($post->attachment_path) {
            Storage::disk('public')->delete($post->attachment_path);
        }

        $post->delete();

        return back()->with('status', 'Post deleted.');
    }
}