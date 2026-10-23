@extends('layouts.app')

@section('title', 'Messages · TUP Opportunity Hub')

@section('content')
<div class="mb-6">
    <p class="eyebrow">Inbox</p>
    <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Messages</h1>
</div>

<div class="card grid h-[calc(100vh-14rem)] min-h-[520px] overflow-hidden md:grid-cols-3">

    {{-- Conversation list --}}
    <aside class="flex flex-col border-b border-slate-200 md:border-b-0 md:border-r">
        <div class="border-b border-slate-200 px-4 py-3">
            <h2 class="text-sm font-semibold text-slate-900">Conversations</h2>
        </div>

        <div class="flex-1 overflow-y-auto">
            @forelse ($conversations as $convo)
                @php $isActive = optional($activeUser)->id === $convo['user']->id; @endphp
                <a href="{{ route('messages.index', ['with' => $convo['user']->id]) }}"
                   @if ($isActive) aria-current="true" @endif
                   class="block border-b border-slate-100 px-4 py-3 transition hover:bg-slate-50 {{ $isActive ? 'bg-brand-50' : '' }}">
                    <div class="flex items-center justify-between gap-2">
                        <span class="truncate text-sm font-medium text-slate-900">{{ $convo['user']->name }}</span>
                        @if ($convo['unread'] > 0)
                            <span class="rounded-full bg-brand-700 px-2 py-0.5 text-xs font-semibold text-white"
                                  aria-label="{{ $convo['unread'] }} unread">{{ $convo['unread'] }}</span>
                        @endif
                    </div>
                    <p class="mt-0.5 truncate text-xs text-slate-500">{{ $convo['last']->body }}</p>
                    <p class="mt-1 text-[11px] text-slate-400">{{ $convo['last']->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <div class="p-6 text-center">
                    <p class="text-sm font-medium text-slate-900">No conversations yet</p>
                    <p class="mt-1 text-sm text-slate-500">Message a seller from the Sell Wall to start one.</p>
                    <a href="{{ route('sell.index') }}" class="btn-secondary mt-4 text-xs">Browse Sell Wall</a>
                </div>
            @endforelse
        </div>
    </aside>

    {{-- Thread --}}
    <section class="flex min-h-0 flex-col md:col-span-2">
        @if ($activeUser)
            <header class="flex items-center gap-3 border-b border-slate-200 px-5 py-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700" aria-hidden="true">
                    {{ mb_strtoupper(mb_substr($activeUser->name, 0, 1)) }}
                </span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">{{ $activeUser->name }}</h2>
                    <p class="text-xs text-slate-500">
                        {{ \App\Models\User::TYPES[$activeUser->member_type] ?? 'Member' }}@if ($activeUser->program) · {{ $activeUser->program }}@endif
                    </p>
                </div>
            </header>

            <div id="thread" class="flex-1 space-y-3 overflow-y-auto bg-slate-50 px-5 py-4" aria-live="polite">
                @forelse ($thread as $msg)
                    @php $mine = $msg->sender_id === auth()->id(); @endphp
                    <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[80%] rounded-2xl px-4 py-2.5 text-sm shadow-sm
                                    {{ $mine ? 'rounded-br-sm bg-brand-700 text-white' : 'rounded-bl-sm border border-slate-200 bg-white text-slate-800' }}">
                            @if ($msg->listing)
                                <p class="mb-1 text-xs {{ $mine ? 'text-brand-100' : 'text-slate-500' }}">Re: {{ $msg->listing->title }}</p>
                            @endif
                            <p class="whitespace-pre-line leading-6">{{ $msg->body }}</p>
                            <time datetime="{{ $msg->created_at->toIso8601String() }}"
                                  class="mt-1 block text-right text-[11px] {{ $mine ? 'text-brand-100' : 'text-slate-400' }}">
                                {{ $msg->created_at->format('M j, g:i A') }}
                            </time>
                        </div>
                    </div>
                @empty
                    <div class="flex h-full items-center justify-center">
                        <p class="text-sm text-slate-500">No messages yet. Say hello to start the conversation.</p>
                    </div>
                @endforelse
            </div>

            <form method="POST" action="{{ route('messages.store') }}" class="border-t border-slate-200 bg-white p-4">
                @csrf
                <input type="hidden" name="recipient_id" value="{{ $activeUser->id }}">
                <label for="message-body" class="sr-only">Message</label>
                <div class="flex items-end gap-3">
                    <textarea id="message-body" name="body" rows="2" required maxlength="2000"
                              placeholder="Write a message. Press Enter to send, Shift+Enter for a new line."
                              class="input resize-none"></textarea>
                    <button type="submit" class="btn-primary h-10">Send</button>
                </div>
            </form>
        @else
            <div class="flex flex-1 items-center justify-center p-8 text-center">
                <div>
                    <p class="text-sm font-medium text-slate-900">Select a conversation</p>
                    <p class="mt-1 text-sm text-slate-500">Choose someone on the left to read messages and reply.</p>
                </div>
            </div>
        @endif
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const thread = document.getElementById('thread');
        if (thread) thread.scrollTop = thread.scrollHeight;

        const input = document.getElementById('message-body');
        if (input) {
            input.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault();
                    if (input.value.trim() !== '') input.form.submit();
                }
            });
        }
    });
</script>
@endsection