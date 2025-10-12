<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl"> {{-- Added dir="rtl" for explicit right-to-left --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-700"> {{-- Changed base background and text color --}}
<div class="min-h-screen">
    {{-- Assuming layouts.navigation handles the main app navigation and potentially the Laravel logo --}}
    {{-- You might need to edit 'resources/views/layouts/navigation.blade.php' directly to remove any Laravel logos. --}}
    @include('layouts.navigation')

    {{-- Fetch notifications directly in the view for simplicity, or ideally, pass from a View Composer/Controller --}}
    @php
        $notifications = \App\Models\Notification::where('is_read', false)->latest()->take(5)->get();
        $unreadCount = $notifications->count();
    @endphp

    {{-- ✅ Fixed Top Bar: Quick Links + Notifications --}}
    <div class="bg-white shadow-md sticky top-0 z-20 border-b border-slate-200"> {{-- Increased shadow and added subtle border --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between"> {{-- Increased vertical padding --}}
            {{-- Quick Links --}}
            <div class="flex items-center space-x-4 rtl:space-x-reverse"> {{-- Aligned items, increased spacing --}}
                <a href="{{ route('clients.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 rtl:mr-2 rtl:ml-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.121-1.278-.344-1.84M7 20h4V10.333c0-.272-.114-.53-.323-.722L3 4m0 0L2.25 3.25M3 4l-1.25 1.25M2.25 3.25L3 4" />
                    </svg>
                    {{ __('العملاء') }}
                </a>
                <a href="{{ route('debts.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 rtl:mr-2 rtl:ml-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V9m0 3v1m0 3v1h.01M12 3a9 9 0 100 18 9 9 0 000-18z" />
                    </svg>
                    {{ __('الديون') }}
                </a>
                <a href="{{ route('payments.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-fuchsia-600 hover:bg-fuchsia-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 rtl:mr-2 rtl:ml-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    {{ __('الدفعات') }}
                </a>
                <a href="{{ route('transactions.all') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow-md transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 rtl:mr-2 rtl:ml-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    {{ __('البحث السريع') }}
                </a>
            </div>

            {{-- Notification Bell Icon and Dropdown --}}
            <div class="relative">
                <button id="notifBtn" class="relative p-2 rounded-full text-slate-600 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs text-white bg-red-600 rounded-full font-bold">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>

                <div id="notifDropdown" class="hidden absolute left-0 rtl:right-0 rtl:left-auto mt-3 w-80 bg-white border border-slate-200 shadow-xl rounded-lg z-50 overflow-hidden transform origin-top-left rtl:origin-top-right"> {{-- Adjusted position to left-0 (for LTR) and right-0 (for RTL) --}}
                    <div class="p-4 font-bold border-b border-slate-200 text-slate-800 text-lg">
                        {{ __('الإشعارات') }}
                    </div>
                    <ul class="max-h-72 overflow-y-auto divide-y divide-slate-100"> {{-- Increased max height, added subtle divider --}}
                        @forelse($notifications as $notification)
                            <li class="px-4 py-3 hover:bg-slate-50 transition-colors duration-150">
                                <a href="{{ route('notifications.index') }}" class="block"> {{-- Made the whole item clickable --}}
                                    <div class="flex items-start justify-between">
                                        <div class="flex-grow">
                                            <div class="font-semibold text-slate-800">{{ $notification->title }}</div>
                                            <div class="text-slate-600 text-sm mt-1">{{ Str::limit($notification->body, 60) }}</div>
                                        </div>
                                        <div class="flex-shrink-0 text-left rtl:text-right ml-2 rtl:mr-2"> {{-- Adjusted margin for RTL --}}
                                            <span class="text-xs text-slate-500">{{ $notification->created_at->diffForHumans() }}</span>
                                            @if(!$notification->is_read)
                                                <span class="block text-xs bg-red-500 text-white px-2 py-0.5 rounded-full font-bold mt-1 text-center">{{ __('جديد') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="px-4 py-6 text-slate-500 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    {{ __('لا توجد إشعارات جديدة حالياً.') }}
                                </div>
                            </li>
                        @endforelse
                    </ul>
                    <div class="text-center p-3 border-t border-slate-200"> {{-- Added top border --}}
                        <a href="{{ route('notifications.index') }}" class="text-sky-600 hover:text-sky-700 hover:underline text-sm font-medium transition-colors duration-150">
                            {{ __('عرض كل الإشعارات') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Header --}}
    @if (isset($header))
        <header class="bg-white shadow-sm mt-4 rounded-lg mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6"> {{-- Added top margin and rounded corners --}}
            <div class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
                {{ $header }}
            </div>
        </header>
    @endif

    {{-- Page Content --}}
    <main class="py-6"> {{-- Adjusted padding for main content --}}
        {{ $slot }}
    </main>
</div>

{{-- Notification Dropdown Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const notifBtn = document.getElementById("notifBtn");
        const notifDropdown = document.getElementById("notifDropdown");

        notifBtn.addEventListener("click", function (e) {
            e.stopPropagation(); // Prevents the click from immediately closing the dropdown via the document listener
            notifDropdown.classList.toggle("hidden");
        });

        document.addEventListener("click", function (e) {
            // Close dropdown if click is outside the button and the dropdown itself
            if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.classList.add("hidden");
            }
        });
    });
</script>
</body>
</html>
