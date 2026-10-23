@extends('layouts.app')

@section('title', 'Apply · TUP Opportunity Hub')

@section('content')
<div class="mx-auto max-w-2xl">
    <nav aria-label="Breadcrumb" class="mb-6 text-sm text-slate-500">
        <a href="{{ route('feed.index') }}" class="hover:text-slate-900">Newsfeed</a>
        <span aria-hidden="true"> / </span>
        <span class="text-slate-900">Apply</span>
    </nav>

    <p class="eyebrow">{{ \App\Models\Post::CATEGORIES[$post->category] ?? '' }}</p>
    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Apply to this opportunity</h1>
    <p class="mt-1 text-sm text-slate-500">Posted by {{ $post->user->name }}</p>

    <section class="card mt-6 p-6 sm:p-7">
        <p class="whitespace-pre-line text-[15px] leading-7 text-slate-700">{{ $post->body }}</p>

        <p class="mt-4 text-xs text-slate-500">
            {{ $post->deadline ? 'Deadline '.$post->deadline->format('M j, Y') : 'No deadline' }}
        </p>

        @if ($post->requirement_path)
            <a href="{{ route('feed.requirement', $post) }}"
               class="mt-5 flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm hover:border-brand-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500">
                <span class="min-w-0 truncate font-medium text-slate-800">{{ $post->requirement_name }}</span>
                <span class="shrink-0 font-semibold text-brand-700">Download</span>
            </a>
            <p class="mt-2 text-xs text-slate-500">Download the requirements, complete them, then upload your file below.</p>
        @endif
    </section>

    <form method="POST" action="{{ route('applications.store', $post) }}" enctype="multipart/form-data"
          class="card mt-6 space-y-6 p-6 sm:p-7">
        @csrf

        <div>
            <label for="message" class="label">Message to the poster <span class="font-normal text-slate-500">(optional)</span></label>
            <textarea id="message" name="message" rows="4" maxlength="1000" class="input"
                      placeholder="Introduce yourself and anything the poster should know.">{{ old('message') }}</textarea>
        </div>

        <div>
            <label for="file" class="label">Your application file <span class="text-red-600">*</span></label>
            <input id="file" type="file" name="file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                   class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100">
            <p class="mt-1 text-xs text-slate-500">PDF, Word, JPG or PNG. Up to 5 MB.</p>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
            <a href="{{ route('feed.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Submit application</button>
        </div>
    </form>
</div>
@endsection