@extends('layouts.app')

@section('title', 'Dashboard · TUP Opportunity Hub')

@section('content')
@php
    $canPost = $user->member_type !== 'student';

    $statCards = [
        ['label' => 'My listings',     'value' => $stats['my_listings'],     'href' => route('sell.index')],
        ['label' => 'Unread messages', 'value' => $stats['unread'],          'href' => route('messages.index')],
        ['label' => 'My applications', 'value' => $stats['my_applications'], 'href' => route('feed.index')],
    ];

    if ($canPost) {
        $statCards[] = ['label' => 'Applicants to my posts', 'value' => $stats['applicants'], 'href' => route('feed.index')];
    }
@endphp

<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="eyebrow">{{ now()->format('l, F j, Y') }}</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Welcome back, {{ $user->name }}</h1>
        <p class="mt-1 text-sm text-slate-500">
            {{ $user->typeLabel() }}@if ($user->program) · {{ $user->program }}@endif
        </p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('feed.index') }}" class="btn-primary">
            {{ $canPost ? 'Post an opportunity' : 'Browse opportunities' }}
        </a>
        <a href="{{ route('sell.create') }}" class="btn-secondary">New listing</a>
    </div>
</div>

<dl class="grid grid-cols-2 gap-4 lg:grid-cols-{{ count($statCards) }}">
    @foreach ($statCards as $stat)
        <div class="card p-5">
            <dt class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</dt>
            <dd class="mt-2 text-3xl font-semibold tabular-nums text-slate-900">{{ $stat['value'] }}</dd>
            <a href="{{ $stat['href'] }}" class="mt-3 inline-block text-sm font-medium text-brand-700 hover:underline">View details</a>
        </div>
    @endforeach
</dl>

{{-- Opportunities --}}
<section class="card mt-8 p-6 sm:p-7" aria-labelledby="opportunities-heading">
    <div class="mb-5 flex items-center justify-between gap-4">
        <div>
            <h2 id="opportunities-heading" class="text-base font-semibold text-slate-900">Open opportunities</h2>
            <p class="mt-0.5 text-sm text-slate-500">Hiring, applications, scholarships and more.</p>
        </div>
        <a href="{{ route('feed.index') }}" class="text-sm font-medium text-brand-700 hover:underline">View all</a>
    </div>

    @forelse ($openOpportunities as $post)
        @php
            $isOwner = $post->user_id === $user->id;
            $applied = in_array($post->id, $appliedPostIds, true);
            $reason  = $isOwner ? null : $post->applyBlockReason($user);
        @endphp

        <div class="flex flex-col gap-3 border-t border-slate-100 py-5 first:border-t-0 first:pt-0 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="badge bg-slate-100 text-slate-700">{{ \App\Models\Post::CATEGORIES[$post->category] ?? '' }}</span>
                    @if ($post->isScholarship())
                        <span class="badge bg-rose-50 text-rose-700">Students only</span>
                    @endif
                </div>
                <p class="mt-2 text-sm font-medium text-slate-900">{{ \Illuminate\Support\Str::limit($post->body, 110) }}</p>
                <p class="mt-1 text-xs text-slate-500">
                    {{ $post->user->name }}
                    · {{ $post->deadline ? 'Deadline '.$post->deadline->format('M j, Y') : 'No deadline' }}
                    @if ($isOwner) · {{ $post->applications_count }} {{ \Illuminate\Support\Str::plural('applicant', $post->applications_count) }} @endif
                </p>
            </div>

            <div class="shrink-0">
                @if ($isOwner)
                    <a href="{{ route('applications.index', $post) }}" class="btn-secondary text-xs">View applicants</a>
                @elseif ($applied)
                    <span class="badge bg-emerald-100 text-emerald-800">You applied</span>
                @elseif ($reason)
                    <p class="text-xs text-slate-500">{{ $reason }}</p>
                @else
                    <a href="{{ route('applications.create', $post) }}" class="btn-primary text-xs">Apply</a>
                @endif
            </div>
        </div>
    @empty
        <div class="py-10 text-center">
            <p class="text-sm font-medium text-slate-900">No open opportunities right now</p>
            <p class="mt-1 text-sm text-slate-500">New hiring and scholarship posts will appear here.</p>
        </div>
    @endforelse
</section>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <section class="card p-6" aria-labelledby="feed-heading">
        <div class="mb-4 flex items-center justify-between">
            <h2 id="feed-heading" class="text-base font-semibold text-slate-900">Latest from the newsfeed</h2>
            <a href="{{ route('feed.index') }}" class="text-sm font-medium text-brand-700 hover:underline">View all</a>
        </div>

        @forelse ($recentPosts as $post)
            <article class="border-t border-slate-100 py-4 first:border-t-0 first:pt-0">
                <p class="text-xs text-slate-500">
                    {{ \App\Models\Post::CATEGORIES[$post->category] ?? $post->category }} · {{ $post->user->name }} · {{ $post->created_at->diffForHumans() }}
                </p>
                <p class="mt-1 text-sm leading-6 text-slate-700">{{ \Illuminate\Support\Str::limit($post->body, 140) }}</p>
            </article>
        @empty
            <div class="py-8 text-center">
                <p class="text-sm font-medium text-slate-900">No posts yet</p>
                <p class="mt-1 text-sm text-slate-500">Share a job opening or event to start the feed.</p>
            </div>
        @endforelse
    </section>

    <section class="card p-6" aria-labelledby="sell-heading">
        <div class="mb-4 flex items-center justify-between">
            <h2 id="sell-heading" class="text-base font-semibold text-slate-900">Fresh on the sell wall</h2>
            <a href="{{ route('sell.index') }}" class="text-sm font-medium text-brand-700 hover:underline">Browse all</a>
        </div>

        @forelse ($recentListings as $listing)
            <div class="flex items-start justify-between gap-4 border-t border-slate-100 py-4 first:border-t-0 first:pt-0">
                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-slate-900">{{ $listing->title }}</p>
                    <p class="mt-0.5 text-xs text-slate-500">{{ $listing->user->name }} · {{ \App\Models\Listing::CATEGORIES[$listing->category] ?? '' }}</p>
                </div>
                <p class="whitespace-nowrap text-sm font-semibold text-slate-900">
                    {{ $listing->price ? '₱'.number_format($listing->price, 2) : 'Free' }}
                </p>
            </div>
        @empty
            <div class="py-8 text-center">
                <p class="text-sm font-medium text-slate-900">Nothing for sale yet</p>
                <p class="mt-1 text-sm text-slate-500">List an item or service you want to offer to other members.</p>
            </div>
        @endforelse
    </section>
</div>
@endsection