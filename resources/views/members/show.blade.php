@extends('layouts.app')

@section('title', $member->name.' · TUP Opportunity Hub')

@section('content')
<div class="mx-auto max-w-3xl">
    <nav aria-label="Breadcrumb" class="mb-6 text-sm text-slate-500">
        <a href="{{ route('feed.index') }}" class="hover:text-slate-900">Newsfeed</a>
        <span aria-hidden="true"> / </span>
        <span class="text-slate-900">{{ $member->name }}</span>
    </nav>

    <section class="card p-8" aria-labelledby="member-name">
        <div class="flex flex-col items-start gap-6 sm:flex-row sm:items-center">
            <span class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-brand-100 text-3xl font-semibold text-brand-700" aria-hidden="true">
                {{ mb_strtoupper(mb_substr($member->name, 0, 1)) }}
            </span>

            <div class="min-w-0 flex-1">
                <h1 id="member-name" class="text-2xl font-semibold text-slate-900">{{ $member->name }}</h1>
                <p class="mt-1 text-sm text-slate-600">{{ $member->typeLabel() }}</p>
                <p class="mt-2 flex flex-wrap gap-x-4 text-sm text-slate-500">
                    @if ($member->program) <span>{{ $member->program }}</span> @endif
                    @if ($member->batch_year) <span>Batch {{ $member->batch_year }}</span> @endif
                </p>
            </div>

            @if ($isSelf)
                <a href="{{ route('settings.edit') }}" class="btn-secondary">Edit profile</a>
            @else
                <a href="{{ route('messages.index', ['with' => $member->id]) }}" class="btn-primary">Send message</a>
            @endif
        </div>
    </section>

    <h2 class="mt-10 text-base font-semibold text-slate-900">Recent posts</h2>

    <section class="mt-2 divide-y divide-slate-200" aria-label="Posts by {{ $member->name }}">
        @forelse ($posts as $post)
            <article class="py-8">
                <p class="text-xs text-slate-500">
                    {{ \App\Models\Post::CATEGORIES[$post->category] ?? $post->category }} · {{ $post->created_at->diffForHumans() }}
                </p>
                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $post->body }}</p>
                <a href="{{ route('feed.index') }}#post-{{ $post->id }}" class="mt-3 inline-block text-sm font-medium text-brand-700 hover:underline">
                    View in newsfeed
                </a>
            </article>
        @empty
            <p class="py-8 text-sm text-slate-500">No posts yet.</p>
        @endforelse
    </section>
</div>
@endsection