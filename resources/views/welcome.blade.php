<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>مرحباً بك</title> {{-- Changed title to Arabic and more generic --}}

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    {{-- IMPORTANT: In a real Laravel project, ensure you compile your Tailwind CSS.
         You would typically link it like this: @vite(['resources/css/app.css', 'resources/js/app.js'])
         I've removed the large inline style block as it's not how Tailwind is used professionally.
         Make sure your app.css includes compiled Tailwind. --}}
    <style>
        /* Placeholder for compiled Tailwind CSS */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f1f5f9; /* slate-100 */
            color: #334155; /* slate-700 */
            line-height: 1.6;
        }
        .container {
            max-width: 1280px; /* max-w-7xl */
            margin-left: auto;
            margin-right: auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .bg-gradient-custom {
            background: linear-gradient(to right bottom, #3b82f6, #60a5fa); /* blue-500 to blue-400 */
        }
        .shadow-custom {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .hover-scale:hover {
            transform: scale(1.01);
        }
        .transition-ease {
            transition: all 0.3s ease-in-out;
        }
        /* Basic flex and grid for responsiveness, assuming a base Tailwind setup */
        .flex-center { display: flex; justify-content: center; align-items: center; }
        .grid-cols-1 { display: grid; grid-template-columns: repeat(1, minmax(0, 1fr)); }
        @media (min-width: 768px) {
            .md\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (min-width: 1024px) {
            .lg\:grid-cols-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }
        .p-6 { padding: 1.5rem; }
        .py-12 { padding-top: 3rem; padding-bottom: 3rem; }
        .mb-8 { margin-bottom: 2rem; }
        .mt-4 { margin-top: 1rem; }
        .mt-8 { margin-top: 2rem; }
        .mt-16 { margin-top: 4rem; }
        .text-2xl { font-size: 1.5rem; }
        .text-4xl { font-size: 2.25rem; }
        .font-bold { font-weight: 700; }
        .text-center { text-align: center; }
        .rounded-lg { border-radius: 0.5rem; }
        .text-white { color: #fff; }
        .text-gray-900 { color: #1a202c; } /* Adjust to slate-800 if desired */
        .text-gray-600 { color: #4a5568; } /* Adjust to slate-600 if desired */
        .text-gray-500 { color: #718096; } /* Adjust to slate-500 if desired */
        .text-sm { font-size: 0.875rem; }
        .underline { text-decoration: underline; }
        .bg-white { background-color: #fff; }
        .bg-indigo-50 { background-color: #eef2ff; }
        .text-indigo-600 { color: #4f46e5; }
        .rounded-full { border-radius: 9999px; }
        .h-12 { height: 3rem; }
        .w-12 { width: 3rem; }
        .h-6 { height: 1.5rem; }
        .w-6 { width: 1.5rem; }
        .stroke-indigo-500 { stroke: #6366f1; }
        .ring-1 { border-width: 1px; }
        .ring-indigo-200 { border-color: #e0e7ff; }
        .focus\:outline-none:focus { outline: none; }
        .focus\:ring-2:focus { box-shadow: 0 0 0 2px; }
        .focus\:ring-indigo-500:focus { border-color: #6366f1; }
    </style>
</head>
<body class="antialiased">
<div class="min-h-screen flex flex-col">
    {{-- Navigation Bar --}}
    <header class="bg-white shadow-sm py-4">
        <div class="container flex justify-between items-center">
            {{-- Site Title / Logo (placeholder) --}}
            <a href="{{ url('/') }}" class="text-2xl font-bold text-slate-800 hover:text-slate-900 transition-colors">
                {{ config('app.name', 'اسم التطبيق') }} {{-- Use app name --}}
            </a>

            {{-- Auth Links --}}
            <nav class="flex items-center space-x-4 rtl:space-x-reverse">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            {{ __('لوحة التحكم') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            {{ __('تسجيل الدخول') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                {{ __('تسجيل جديد') }}
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    {{-- Hero Section --}}
    <main class="flex-grow flex items-center justify-center py-12 bg-gradient-custom text-white">
        <div class="container text-center">
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">
                {{ __('مرحباً بك في تطبيقك الجديد!') }}
            </h1>
            <p class="text-xl md:text-2xl opacity-90 mb-8">
                {{ __('مكانك الأمثل لإدارة مشاريعك وعملائك بكفاءة.') }}
            </p>
            @guest
                <a href="{{ route('register') }}"
                   class="inline-block bg-white text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-300
                              px-8 py-4 rounded-lg text-lg font-bold shadow-lg hover-scale transition-ease">
                    {{ __('ابدأ الآن مجاناً') }}
                </a>
            @else
                <a href="{{ url('/dashboard') }}"
                   class="inline-block bg-white text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-300
                              px-8 py-4 rounded-lg text-lg font-bold shadow-lg hover-scale transition-ease">
                    {{ __('اذهب إلى لوحة التحكم') }}
                </a>
            @endguest
        </div>
    </main>

    {{-- Feature Section --}}
    <section class="py-16 bg-slate-50">
        <div class="container">
            <h2 class="text-3xl font-bold text-center text-slate-800 mb-12">
                {{ __('اكتشف الميزات التي نقدمها') }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Feature Card 1 --}}
                <div class="bg-white p-6 rounded-lg shadow-custom hover-scale transition-ease ring-1 ring-slate-200">
                    <div class="flex-center h-16 w-16 bg-indigo-50 text-indigo-600 rounded-full mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-slate-900 mb-2">{{ __('إدارة المشاريع') }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ __('نظم مشاريعك بكفاءة، تابع تقدمها، وحافظ على تنظيم مهام فريقك مع أدواتنا سهلة الاستخدام.') }}
                    </p>
                </div>

                {{-- Feature Card 2 --}}
                <div class="bg-white p-6 rounded-lg shadow-custom hover-scale transition-ease ring-1 ring-slate-200">
                    <div class="flex-center h-16 w-16 bg-green-50 text-green-600 rounded-full mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-slate-900 mb-2">{{ __('تتبع العملاء') }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ __('حافظ على جميع معلومات عملائك في مكان واحد، وتتبع تاريخهم، وتواصل معهم بفاعلية.') }}
                    </p>
                </div>

                {{-- Feature Card 3 --}}
                <div class="bg-white p-6 rounded-lg shadow-custom hover-scale transition-ease ring-1 ring-slate-200">
                    <div class="flex-center h-16 w-16 bg-yellow-50 text-yellow-600 rounded-full mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-slate-900 mb-2">{{ __('التقارير المالية') }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ __('احصل على رؤى واضحة حول ديونك ومدفوعاتك لقرارات مالية أفضل وأكثر ذكاءً.') }}
                    </p>
                </div>

                {{-- Feature Card 4 --}}
                <div class="bg-white p-6 rounded-lg shadow-custom hover-scale transition-ease ring-1 ring-slate-200">
                    <div class="flex-center h-16 w-16 bg-purple-50 text-purple-600 rounded-full mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.115 5.19l.319 1.913A6 6 0 008.11 10.36L9.75 12l-.387.775c-.217.433-.132.956.21 1.298l1.348 1.348c.21.21.329.497.329.795v1.089c0 .426.24.815.622 1.006l.153.076c.433.217.956.132 1.298-.21l.723-.723a8.7 8.7 0 002.288-4.042 1.087 1.087 0 00-.358-1.099l-1.33-1.108c-.251-.21-.582-.299-.905-.245l-1.17.195a1.125 1.125 0 01-.98-.314l-.295-.295a1.125 1.125 0 010-1.591l.13-.132a1.125 1.125 0 011.3-.21l.603.302a.809.809 0 001.086-1.086L14.25 7.5l1.256-.837a4.5 4.5 0 001.528-1.732l.146-.292M6.115 5.19A9 9 0 1017.18 4.64M6.115 5.19A8.965 8.965 0 0112 3c1.929 0 3.716.607 5.18 1.64" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-slate-900 mb-2">{{ __('دعم متواصل') }}</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ __('فريق الدعم لدينا جاهز دائماً لمساعدتك وضمان حصولك على أفضل تجربة ممكنة مع التطبيق.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-8 bg-slate-800 text-white text-center">
        <div class="container text-sm">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'اسم التطبيق') }}. {{ __('جميع الحقوق محفوظة.') }}</p>
            <p class="mt-2 text-slate-400">
                {{ __('بواسطة') }} <a href="https://yourwebsite.com" class="underline hover:text-white">اسم شركتك/مطورك</a>
            </p>
            {{-- You can add Laravel version here if needed, but often removed from public-facing pages --}}
            {{-- <p class="mt-2 text-slate-500">Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p> --}}
        </div>
    </footer>
</div>
</body>
</html>
