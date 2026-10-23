<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $canPost = $request->user()->member_type !== 'student';

        $posts = Post::with([
                'user:id,name,member_type,batch_year,program',
                'comments.user:id,name',
                'votes'        => fn ($q) => $q->where('user_id', auth()->id()),
                'applications' => fn ($q) => $q->where('user_id', auth()->id()),
            ])
            ->withCount(['comments', 'applications'])
            ->withSum('votes as score', 'value')
            ->latest()
            ->paginate(10);

        return view('feed.index', [
            'posts'       => $posts,
            'general'     => Post::GENERAL,
            'opportunity' => $canPost ? Post::OPPORTUNITY : [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $canPost = $user->member_type !== 'student';

        $allowed = $canPost ? array_keys(Post::CATEGORIES) : array_keys(Post::GENERAL);

        $data = $request->validate([
            'category'    => ['required', 'in:'.implode(',', $allowed)],
            'body'        => ['required', 'string', 'max:1000'],
            'attachment'  => ['nullable', 'file', 'max:5120', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png'],
            'deadline'    => ['nullable', 'date', 'after_or_equal:today'],
            'requirement' => ['nullable', 'file', 'max:5120', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
        ], [
            'category.in'        => 'Only alumni and fresh grads can post opportunities.',
            'body.required'      => 'Please write a message before publishing.',
            'attachment.max'     => 'The attachment must be 5 MB or smaller.',
            'attachment.mimes'   => 'Allowed attachments: PDF, Word, PowerPoint, Excel, JPG or PNG.',
            'deadline.after_or_equal' => 'The deadline cannot be in the past.',
            'requirement.max'    => 'The requirements file must be 5 MB or smaller.',
            'requirement.mimes'  => 'Requirements file: PDF, Word, JPG or PNG.',
        ]);

        $post = new Post([
            'category' => $data['category'],
            'body'     => $data['body'],
            'status'   => 'open',
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $post->attachment_path = $file->store('attachments', 'public');
            $post->attachment_name = $file->getClientOriginalName();
        }

        if (array_key_exists($data['category'], Post::OPPORTUNITY)) {
            $post->deadline = $data['deadline'] ?? null;

            if ($request->hasFile('requirement')) {
                $file = $request->file('requirement');
                $post->requirement_path = $file->store('requirements', 'local');
                $post->requirement_name = $file->getClientOriginalName();
            }
        }

        $user->posts()->save($post);

        return redirect()->route('feed.index')->with('status', 'Posted to the newsfeed.');
    }

    public function updateStatus(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id && $post->isOpportunity(), 403);

        $data = $request->validate([
            'status' => ['required', 'in:open,closed'],
        ]);

        $post->update(['status' => $data['status']]);

        return back()->with('status', $data['status'] === 'closed' ? 'Applications closed.' : 'Applications reopened.');
    }

    public function requirement(Post $post)
    {
        abort_unless($post->requirement_path, 404);

        return Storage::disk('local')->download($post->requirement_path, $post->requirement_name);
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        if ($post->attachment_path) {
            Storage::disk('public')->delete($post->attachment_path);
        }

        if ($post->requirement_path) {
            Storage::disk('local')->delete($post->requirement_path);
        }

        foreach ($post->applications()->get() as $application) {
            Storage::disk('local')->delete($application->attachment_path);
        }

        $post->delete();

        return back()->with('status', 'Post deleted.');
    }
}