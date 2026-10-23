@extends('layouts.app')

@section('title', 'New listing · TUP Opportunity Hub')

@section('content')
<div class="mx-auto max-w-2xl">
    <nav aria-label="Breadcrumb" class="mb-4 text-sm text-slate-500">
        <a href="{{ route('sell.index') }}" class="hover:text-slate-900">Sell Wall</a>
        <span aria-hidden="true"> / </span>
        <span class="text-slate-900">New listing</span>
    </nav>

    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Create a listing</h1>
    <p class="mt-1 text-sm text-slate-500">Fields marked with * are required.</p>

    <form method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data" class="card mt-6 space-y-6 p-6">
        @csrf

        <section class="space-y-4">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Details</h2>

            <div>
                <label for="title" class="label">Title *</label>
                <input id="title" name="title" value="{{ old('title') }}" required maxlength="120" class="input"
                       placeholder="e.g. Engineering Mathematics textbook">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="category" class="label">Category *</label>
                    <select id="category" name="category" required class="input">
                        @foreach ($categories as $key => $label)
                            <option value="{{ $key }}" @selected(old('category') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="condition" class="label">Condition</label>
                    <select id="condition" name="condition" class="input">
                        <option value="">Not applicable</option>
                        @foreach ($conditions as $key => $label)
                            <option value="{{ $key }}" @selected(old('condition') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="label">Description *</label>
                <textarea id="description" name="description" rows="5" required maxlength="2000" class="input"
                          placeholder="Describe the item or service, including any delivery or meeting details.">{{ old('description') }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Up to 2,000 characters.</p>
            </div>
        </section>

        <section class="space-y-4 border-t border-slate-100 pt-6">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Price and photo</h2>

            <div>
                <label for="price" class="label">Price</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-slate-500" aria-hidden="true">₱</span>
                    <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price') }}"
                           class="input pl-8" placeholder="0.00">
                </div>
                <p class="mt-1 text-xs text-slate-500">Leave empty to list as free or negotiable.</p>
            </div>

            <div>
                <label for="image" class="label">Photo</label>
                <input id="image" type="file" name="image" accept="image/*"
                       class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100">
                <p class="mt-1 text-xs text-slate-500">JPG, PNG or WebP, up to 2 MB.</p>
            </div>
        </section>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
            <a href="{{ route('sell.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Publish listing</button>
        </div>
    </form>
</div>
@endsection