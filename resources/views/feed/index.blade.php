@extends('layouts.app')

@section('title', 'Newsfeed · TUP Opportunity Hub')

@section('content')
@php
    $tones = [
        'announcement' => 'bg-slate-100 text-slate-700',
        'job'          => 'bg-emerald-50 text-emerald-700',
        'internship'   => 'bg-sky-50 text-sky-700',
        'scholarship'  => 'bg-rose-50 text-rose-700',
        'event'        => 'bg-violet-50 text-violet-700',
        'training'     => 'bg-indigo-50 text-indigo-700',
        'volunteer'    => 'bg-teal-50 text-teal-700',
        'achievement'  => 'bg-amber-50 text-amber-800',
        'question'     => 'bg-orange-50 text-orange-700',
    ];
@endphp

<div class="mx-auto max-w-2xl">

    {{-- Page header --}}
    <header class="mb-8 flex items-end justify-between gap-4">
        <div>
            <p class="eyebrow">Community</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Newsfeed</h1>
            <p class="mt-1 text-sm text-slate-500">Opportunities and updates from TUP graduates and students.</p>
        </div>
        <button type="button" class="btn-primary shrink-0" data-open-composer aria-haspopup="dialog" aria-controls="composer">
            <span aria-hidden="true">+</span> Share update
        </button>
    </header>

    {{-- Posts --}}
    <div class="space-y-6" aria-label="Posts">
        @forelse ($posts as $post)
            @php
                $myVote  = $post->votes->first()?->value;
                $isOwner = $post->user_id === auth()->id();
            @endphp

            <article id="post-{{ $post->id }}"
                     class="card scroll-mt-28 p-6 transition duration-200 hover:shadow-md sm:p-7">

                {{-- Author and category --}}
                <header class="flex items-start justify-between gap-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <a href="{{ route('members.show', $post->user) }}"
                           aria-label="View {{ $post->user->name }}'s profile"
                           class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700 transition hover:ring-2 hover:ring-brand-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                            {{ mb_strtoupper(mb_substr($post->user->name, 0, 1)) }}
                        </a>
                        <div class="min-w-0">
                            <a href="{{ route('members.show', $post->user) }}"
                               class="block truncate text-sm font-semibold text-slate-900 hover:underline">
                                {{ $post->user->name }}
                            </a>
                            <p class="truncate text-xs text-slate-500">
                                {{ \App\Models\User::TYPES[$post->user->member_type] ?? 'Member' }}
                                @if ($post->user->batch_year) · Batch {{ $post->user->batch_year }} @endif
                                @if ($post->user->program) · {{ $post->user->program }} @endif
                            </p>
                        </div>
                    </div>

                    <span class="badge shrink-0 {{ $tones[$post->category] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ \App\Models\Post::CATEGORIES[$post->category] ?? $post->category }}
                    </span>
                </header>

                {{-- Content --}}
                <div class="mt-5">
                    <p class="whitespace-pre-line text-[15px] leading-7 text-slate-700">{{ $post->body }}</p>
                </div>

                @if ($post->attachment_path)
                    <a href="{{ $post->attachmentUrl() }}" download="{{ $post->attachment_name }}"
                       class="mt-5 flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm transition hover:border-brand-500 hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                        <span class="min-w-0 truncate font-medium text-slate-800">{{ $post->attachment_name }}</span>
                        <span class="shrink-0 font-semibold text-brand-700">Download</span>
                    </a>
                @endif

                {{-- Actions --}}
                <footer class="mt-6 flex flex-wrap items-center gap-3 border-t border-slate-100 pt-4">

                    {{-- Voting --}}
                    <div class="inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white p-1" role="group" aria-label="Vote on this post">
                        <form method="POST" action="{{ route('feed.vote', $post) }}">
                            @csrf
                            <input type="hidden" name="value" value="1">
                            <button type="submit" aria-label="Upvote" aria-pressed="{{ $myVote === 1 ? 'true' : 'false' }}"
                                    class="flex h-9 w-9 items-center justify-center rounded-full transition focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500
                                           {{ $myVote === 1 ? 'bg-emerald-100 text-emerald-700' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l5 5a1 1 0 01-1.414 1.414L10 5.414 5.707 9.707a1 1 0 01-1.414-1.414l5-5A1 1 0 0110 3z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>

                        <span class="min-w-[2.5rem] text-center text-sm font-semibold tabular-nums text-slate-900" aria-live="polite">
                            {{ $post->score ?? 0 }}
                        </span>

                        <form method="POST" action="{{ route('feed.vote', $post) }}">
                            @csrf
                            <input type="hidden" name="value" value="-1">
                            <button type="submit" aria-label="Downvote" aria-pressed="{{ $myVote === -1 ? 'true' : 'false' }}"
                                    class="flex h-9 w-9 items-center justify-center rounded-full transition focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500
                                           {{ $myVote === -1 ? 'bg-red-100 text-red-700' : 'text-slate-500 hover:bg-slate-100 hover:text-slate-800' }}">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 17a1 1 0 01-.707-.293l-5-5a1 1 0 011.414-1.414L10 14.586l4.293-4.293a1 1 0 011.414 1.414l-5 5A1 1 0 0110 17z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <time datetime="{{ $post->created_at->toIso8601String() }}"
                          title="{{ $post->created_at->format('M j, Y g:i A') }}"
                          class="text-xs text-slate-500">
                        {{ $post->created_at->diffForHumans() }}
                    </time>

                    @if ($isOwner)
                        <form method="POST" action="{{ route('feed.destroy', $post) }}" class="ms-auto"
                              onsubmit="return confirm('Delete this post? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger text-xs">Delete</button>
                        </form>
                    @endif
                </footer>

                {{-- Comments (collapsed by default) --}}
                <details class="group mt-4">
                    <summary class="flex cursor-pointer list-none items-center justify-between rounded-lg px-1 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                        <span>{{ $post->comments_count }} {{ \Illuminate\Support\Str::plural('comment', $post->comments_count) }}</span>
                        <span class="text-xs text-slate-400 group-open:hidden">Show</span>
                        <span class="hidden text-xs text-slate-400 group-open:inline">Hide</span>
                    </summary>

                    <div class="mt-3 space-y-3">
                        @forelse ($post->comments as $comment)
                            <div class="rounded-xl bg-slate-50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-xs font-semibold text-slate-900">
                                        {{ $comment->user->name }}
                                        <span class="font-normal text-slate-500">· {{ $comment->created_at->diffForHumans() }}</span>
                                    </p>
                                    @if ($comment->user_id === auth()->id())
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                                              onsubmit="return confirm('Delete this comment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-medium text-red-600 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">Delete</button>
                                        </form>
                                    @endif
                                </div>
                                <p class="mt-1.5 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $comment->body }}</p>
                            </div>
                        @empty
                            <p class="px-1 text-sm text-slate-500">No comments yet. Start the conversation.</p>
                        @endforelse

                        <form method="POST" action="{{ route('comments.store', $post) }}" class="flex gap-2 pt-1">
                            @csrf
                            <label for="comment-{{ $post->id }}" class="sr-only">Write a comment</label>
                            <input id="comment-{{ $post->id }}" type="text" name="body" maxlength="500" required
                                   placeholder="Write a comment…" class="input">
                            <button type="submit" class="btn-secondary shrink-0">Reply</button>
                        </form>
                    </div>
                </details>
            </article>
        @empty
            <div class="card px-6 py-16 text-center">
                <p class="text-base font-semibold text-slate-900">Nothing posted yet</p>
                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Be the first to share an opportunity or event with the community.</p>
                <button type="button" class="btn-primary mt-6" data-open-composer>Share an update</button>
            </div>
        @endforelse

        <div class="pt-2">{{ $posts->links() }}</div>
    </div>
</div>

{{-- Share an update popup --}}
<dialog id="composer"
        class="w-full max-w-lg rounded-xl border border-slate-200 p-0 shadow-2xl backdrop:bg-slate-900/50"
        aria-labelledby="composer-title">
    <form method="POST" action="{{ route('feed.store') }}" enctype="multipart/form-data" class="space-y-5 p-6 sm:p-7">
        @csrf

        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 id="composer-title" class="text-lg font-semibold text-slate-900">Share an update</h2>
                <p class="mt-1 text-sm text-slate-500">Visible to all members.</p>
            </div>
            <button type="button" data-close-composer aria-label="Close"
                    class="-mr-2 -mt-2 flex h-9 w-9 items-center justify-center rounded-full text-2xl leading-none text-slate-400 hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                &times;
            </button>
        </div>

        <div>
            <label for="category" class="label">Type</label>
            <select id="category" name="category" required class="input">
                @foreach ($categories as $key => $label)
                    <option value="{{ $key }}" @selected(old('category') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="body" class="label">Message</label>
            <textarea id="body" name="body" rows="5" maxlength="1000" required class="input"
                      placeholder="Describe the opportunity, event or achievement.">{{ old('body') }}</textarea>
            <p class="mt-1 text-xs text-slate-500">Up to 1,000 characters.</p>
        </div>

        <div>
            <label for="attachment" class="label">Attach a file <span class="font-normal text-slate-500">(optional)</span></label>
            <input id="attachment" type="file" name="attachment"
                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png"
                   class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100">
            <p class="mt-1 text-xs text-slate-500">PDF, Word, PowerPoint, Excel, JPG or PNG. Up to 5 MB.</p>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
            <button type="button" data-close-composer class="btn-secondary">Cancel</button>
            <button type="submit" class="btn-primary">Publish</button>
        </div>
    </form>
</dialog>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dialog = document.getElementById('composer');

        document.querySelectorAll('[data-open-composer]').forEach((button) => {
            button.addEventListener('click', () => dialog.showModal());
        });

        document.querySelectorAll('[data-close-composer]').forEach((button) => {
            button.addEventListener('click', () => dialog.close());
        });

        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.close();
        });

        @if ($errors->any())
            dialog.showModal();
        @endif
    });
</script>
@endsection