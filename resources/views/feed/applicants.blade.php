@extends('layouts.app')

@section('title', 'Applicants · TUP Opportunity Hub')

@section('content')
<div class="mx-auto max-w-3xl">
    <nav aria-label="Breadcrumb" class="mb-6 text-sm text-slate-500">
        <a href="{{ route('feed.index') }}" class="hover:text-slate-900">Newsfeed</a>
        <span aria-hidden="true"> / </span>
        <span class="text-slate-900">Applicants</span>
    </nav>

    <header class="mb-8">
        <div class="flex flex-wrap items-center gap-2">
            <span class="badge bg-slate-100 text-slate-700">{{ \App\Models\Post::CATEGORIES[$post->category] ?? '' }}</span>
            <span class="badge {{ $post->isOpen() ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                {{ $post->isOpen() ? 'Open' : 'Closed' }}
            </span>
        </div>

        <h1 class="mt-3 text-2xl font-semibold tracking-tight text-slate-900">Applicants</h1>
        <p class="mt-1 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($post->body, 160) }}</p>
        <p class="mt-2 text-sm text-slate-500">
            {{ $applications->count() }} {{ \Illuminate\Support\Str::plural('applicant', $applications->count()) }}
        </p>

        <div class="mt-5 flex flex-wrap gap-3">
            @if ($post->requirement_path)
                <a href="{{ route('feed.requirement', $post) }}" class="btn-secondary text-xs">Download requirements</a>
            @endif
            <form method="POST" action="{{ route('feed.status', $post) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $post->status === 'open' ? 'closed' : 'open' }}">
                <button type="submit" class="btn-secondary text-xs">
                    {{ $post->status === 'open' ? 'Close applications' : 'Reopen applications' }}
                </button>
            </form>
        </div>
    </header>

    <div class="space-y-4">
        @forelse ($applications as $application)
            <article class="card p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900">{{ $application->user->name }}</p>
                        <p class="mt-0.5 truncate text-xs text-slate-500">
                            {{ \App\Models\User::TYPES[$application->user->member_type] ?? 'Member' }}
                            @if ($application->user->batch_year) · Batch {{ $application->user->batch_year }} @endif
                            @if ($application->user->program) · {{ $application->user->program }} @endif
                            · {{ $application->user->email }}
                        </p>
                    </div>
                    <time datetime="{{ $application->created_at->toIso8601String() }}" class="shrink-0 text-xs text-slate-500">
                        {{ $application->created_at->diffForHumans() }}
                    </time>
                </div>

                @if ($application->message)
                    <p class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $application->message }}</p>
                @endif

                <div class="mt-4 flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <span class="min-w-0 truncate text-sm font-medium text-slate-800">{{ $application->attachment_name }}</span>
                    <a href="{{ route('applications.file', $application) }}" class="btn-secondary shrink-0 text-xs">Download</a>
                </div>
            </article>
        @empty
            <div class="card px-6 py-16 text-center">
                <p class="text-base font-semibold text-slate-900">No applicants yet</p>
                <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Applications will appear here as members apply.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection