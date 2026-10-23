<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TUP Opportunity Hub')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                colors: {
                    brand: { 50: '#eef4ff', 100: '#dbe7ff', 500: '#1d4ed8', 600: '#1e40af', 700: '#1e3a8a', 900: '#0f2a4a' }
                }
            }}
        }
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .card { @apply bg-white rounded-xl border border-slate-200 shadow-sm; }
            .btn-primary { @apply inline-flex items-center justify-center gap-2 rounded-lg bg-brand-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2 disabled:opacity-50 transition; }
            .btn-secondary { @apply inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2 transition; }
            .btn-danger { @apply inline-flex items-center justify-center rounded-lg px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 transition; }
            .input { @apply block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100; }
            .label { @apply mb-1.5 block text-sm font-medium text-slate-700; }
            .badge { @apply inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium; }
            .eyebrow { @apply text-xs font-semibold uppercase tracking-wider text-slate-500; }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">

    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-white focus:px-3 focus:py-2">Skip to content</a>

    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-6 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-900 text-xs font-bold text-white">TUP</span>
                <span class="hidden font-semibold text-slate-900 md:block">Opportunity Hub</span>
            </a>

            @php
                $links = [
                    ['route' => 'dashboard',      'label' => 'Dashboard', 'match' => 'dashboard'],
                    ['route' => 'messages.index', 'label' => 'Messages',  'match' => 'messages.*'],
                    ['route' => 'sell.index',     'label' => 'Sell Wall', 'match' => 'sell.*'],
                    ['route' => 'feed.index',     'label' => 'Newsfeed',  'match' => 'feed.*'],
                ];
            @endphp

            <nav aria-label="Main navigation" class="flex items-center gap-1 overflow-x-auto">
                @foreach ($links as $link)
                    @php $active = request()->routeIs($link['match']); @endphp
                    <a href="{{ route($link['route']) }}"
                       @if ($active) aria-current="page" @endif
                       class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium transition
                              {{ $active ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-3">
                <div class="hidden text-right lg:block">
                    <p class="text-sm font-medium text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ auth()->user()->typeLabel() }}</p>
                </div>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700" aria-hidden="true">
                    {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                </span>
                <a href="{{ route('settings.edit') }}" class="btn-secondary text-xs">Settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary text-xs">Log out</button>
                </form>
            </div>
        </div>
    </header>

    <main id="main" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('status'))
            <div role="status" class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <span class="font-semibold">Success.</span>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-semibold">Please fix the following:</p>
                <ul class="mt-1 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-16 border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-6 text-xs text-slate-500 sm:px-6 lg:px-8">
            TUP Opportunity Hub · Technological University of the Philippines
        </div>
    </footer>

</body>
</html>