@extends('layouts.app')

@section('title', 'Dashboard · TUP Opportunity Hub')

@section('content')
@php
    $statCards = [
        ['label' => 'My listings',     'value' => $stats['my_listings'], 'href' => route('sell.index')],
        ['label' => 'Unread messages', 'value' => $stats['unread'],      'href' => route('messages.index')],
    ];
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
        <a href="{{ route('sell.create') }}" class="btn-primary">New listing</a>
        <a href="{{ route('feed.index') }}" class="btn-secondary">Write a post</a>
    </div>
</div>

<dl class="grid grid-cols-2 gap-4 lg:grid-cols-5">
    @foreach ($statCards as $stat)
        <div class="card p-5">
            <dt class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</dt>
            <dd class="mt-2 text-3xl font-semibold tabular-nums text-slate-900">{{ $stat['value'] }}</dd>
            @if ($stat['href'])
                <a href="{{ $stat['href'] }}" class="mt-3 inline-block text-sm font-medium text-brand-700 hover:underline">View details</a>
            @endif
        </div>
    @endforeach
</dl>

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
                <a href="{{ route('feed.index') }}" class="btn-secondary mt-4">Write a post</a>
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
                <a href="{{ route('sell.create') }}" class="btn-secondary mt-4">Create a listing</a>
            </div>
        @endforelse
    </section>
</div>
@endsection