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
            .input { @apply block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100; }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800 antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded focus:bg-white focus:px-3 focus:py-2">Skip to content</a>

    <div id="main" class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <a href="/" class="mb-6 flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-900 text-sm font-bold text-white">TUP</span>
            <span class="text-lg font-semibold text-slate-900">Opportunity Hub</span>
        </a>

        <div class="card w-full max-w-md p-8">
            {{ $slot }}
        </div>

        <p class="mt-6 text-xs text-slate-500">Technological University of the Philippines</p>
    </div>
</body>
</html>