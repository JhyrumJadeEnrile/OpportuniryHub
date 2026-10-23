<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function create(Request $request, Post $post)
    {
        $user = $request->user();

        if ($post->applications()->where('user_id', $user->id)->exists()) {
            return redirect()->route('feed.index')->with('status', 'You already applied to this post.');
        }

        if ($reason = $post->applyBlockReason($user)) {
            return redirect()->route('feed.index')->withErrors(['apply' => $reason]);
        }

        return view('feed.apply', ['post' => $post]);
    }

    public function store(Request $request, Post $post): RedirectResponse
    {
        $user = $request->user();

        if ($reason = $post->applyBlockReason($user)) {
            return redirect()->route('feed.index')->withErrors(['apply' => $reason]);
        }

        if ($post->applications()->where('user_id', $user->id)->exists()) {
            return redirect()->route('feed.index')->with('status', 'You already applied to this post.');
        }

        $data = $request->validate([
            'message' => ['nullable', 'string', 'max:1000'],
            'file'    => ['required', 'file', 'max:5120', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
        ], [
            'file.required' => 'Please upload your application file.',
            'file.max'      => 'The file must be 5 MB or smaller.',
            'file.mimes'    => 'Allowed files: PDF, Word, JPG or PNG.',
        ]);

        $file = $request->file('file');

        $post->applications()->create([
            'user_id'         => $user->id,
            'message'         => $data['message'] ?? null,
            'attachment_path' => $file->store('applications/'.$post->id, 'local'),
            'attachment_name' => $file->getClientOriginalName(),
        ]);

        return redirect()->route('feed.index')->with('status', 'Application submitted.');
    }

    public function index(Request $request, Post $post): View
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        return view('feed.applicants', [
            'post'         => $post,
            'applications' => $post->applications()->with('user:id,name,email,member_type,batch_year,program')->get(),
        ]);
    }

    public function file(Request $request, Application $application)
    {
        $application->load('post');

        $isPoster    = $application->post->user_id === $request->user()->id;
        $isApplicant = $application->user_id === $request->user()->id;

        abort_unless($isPoster || $isApplicant, 403);

        return Storage::disk('local')->download($application->attachment_path, $application->attachment_name);
    }
}