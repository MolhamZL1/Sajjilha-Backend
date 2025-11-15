<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'اسم التطبيق') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-700 antialiased bg-slate-100">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div>
        <a href="/" class="flex items-center justify-center mb-4"> {{-- Added flex for alignment --}}
            {{-- Professional Debts Logo (SVG) --}}
            <svg class="h-16 w-auto text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                {{-- Icon representing ledger/money/debt --}}
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                <path d="M16 13h-4V8h-2v5H6"></path>
            </svg>
            <span class="text-4xl font-extrabold text-slate-800 tracking-tighter mr-2 rtl:ml-2 rtl:mr-0">Debts</span> {{-- Styled "Debts" text --}}
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-xl rounded-xl overflow-hidden">
        {{ $slot }}
    </div>
</div>
</body>
</html>
