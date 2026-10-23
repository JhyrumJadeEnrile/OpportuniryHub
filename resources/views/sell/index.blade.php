@extends('layouts.app')

@section('title', 'Sell Wall · TUP Opportunity Hub')

@section('content')
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="eyebrow">Marketplace</p>
        <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Sell Wall</h1>
        <p class="mt-1 text-sm text-slate-500">Items and services offered by TUP members.</p>
    </div>
    <a href="{{ route('sell.create') }}" class="btn-primary">New listing</a>
</div>

<form method="GET" action="{{ route('sell.index') }}" role="search" class="card mb-6 flex flex-col gap-3 p-4 sm:flex-row sm:items-end">
    <div class="flex-1">
        <label for="q" class="label">Search</label>
        <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Search by title" class="input">
    </div>
    <div class="sm:w-56">
        <label for="category" class="label">Category</label>
        <select id="category" name="category" class="input">
            <option value="">All categories</option>
            @foreach ($categories as $key => $label)
                <option value="{{ $key }}" @selected(request('category') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="btn-primary">Apply</button>
        @if (request()->filled('q') || request()->filled('category'))
            <a href="{{ route('sell.index') }}" class="btn-secondary">Clear</a>
        @endif
    </div>
</form>

@if ($listings->isEmpty())
    <div class="card p-10 text-center">
        <p class="text-base font-semibold text-slate-900">No listings found</p>
        <p class="mt-1 text-sm text-slate-500">
            {{ request()->filled('q') || request()->filled('category') ? 'Try a different search or category.' : 'Be the first to list something for sale.' }}
        </p>
    </div>
@endif

<div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
    @foreach ($listings as $listing)
        <article class="card flex flex-col overflow-hidden">
            @if ($listing->imageUrl())
                <img src="{{ $listing->imageUrl() }}" alt="Photo of {{ $listing->title }}" class="h-48 w-full object-cover">
            @else
                <div class="flex h-48 w-full items-center justify-center bg-slate-100 text-sm text-slate-400" aria-hidden="true">No photo</div>
            @endif

            <div class="flex flex-1 flex-col p-5">
                <div class="flex items-start justify-between gap-3">
                    <h2 class="text-base font-semibold text-slate-900">{{ $listing->title }}</h2>
                    <p class="whitespace-nowrap text-base font-semibold tabular-nums text-slate-900">
                        {{ $listing->price ? '₱'.number_format($listing->price, 2) : 'Free' }}
                    </p>
                </div>

                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="badge bg-slate-100 text-slate-700">{{ \App\Models\Listing::CATEGORIES[$listing->category] ?? '' }}</span>
                    @if ($listing->condition)
                        <span class="badge bg-brand-50 text-brand-700">{{ \App\Models\Listing::CONDITIONS[$listing->condition] }}</span>
                    @endif
                </div>

                <p class="mt-3 flex-1 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($listing->description, 120) }}</p>

                <p class="mt-4 text-xs text-slate-500">
                    Seller: {{ $listing->user->name }} · {{ \App\Models\User::TYPES[$listing->user->member_type] ?? 'Member' }}
                </p>

                <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
                    @if ($listing->user_id !== auth()->id())
                        <a href="{{ route('messages.index', ['with' => $listing->user_id]) }}" class="btn-primary text-xs">Message seller</a>
                    @else
                        <span class="text-xs font-medium text-slate-500">Your listing</span>
                        <div class="ms-auto flex gap-2">
                            <form method="POST" action="{{ route('sell.sold', $listing) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-secondary text-xs">Mark as sold</button>
                            </form>
                            <form method="POST" action="{{ route('sell.destroy', $listing) }}"
                                  onsubmit="return confirm('Remove this listing? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger text-xs">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </article>
    @endforeach
</div>

<div class="mt-8">{{ $listings->links() }}</div>
@endsection