<x-guest-layout>
    <x-auth-session-status class="mb-6 text-center font-medium text-sm text-green-600" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h2 class="text-2xl font-bold text-center text-slate-800 mb-8">{{ __('تسجيل الدخول') }}</h2>

        <div class="mb-4">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600" />
        </div>

        <div class="mb-4">
            <x-input-label for="password" :value="__('كلمة المرور')" />
            <x-text-input
                id="password"
                class="block mt-1 w-full border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500"
                    name="remember"
                />
                <span class="ms-2 text-sm text-slate-600">{{ __('تذكرني') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6"> {{-- Changed to justify-between for spacing --}}
            <div class="flex items-center"> {{-- Grouped forgotten password and register link --}}
                @if (Route::has('password.request'))
                    <a
                        class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-150"
                        href="{{ route('password.request') }}"
                    >
                        {{ __('هل نسيت كلمة المرور؟') }}
                    </a>
                @endif

                {{-- Link to Register Page --}}
                @if (Route::has('register'))
                    <a
                        class="underline text-sm text-sky-600 hover:text-sky-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-150 ms-4 rtl:me-4 rtl:ms-0" {{-- Added margin and unique color --}}
                    href="{{ route('register') }}"
                    >
                        {{ __('إنشاء حساب جديد') }}
                    </a>
                @endif
            </div>

            <x-primary-button type="submit" class="bg-sky-600 hover:bg-sky-700 focus:ring-sky-500 transition-all duration-150 transform hover:-translate-y-0.5">
                {{ __('تسجيل الدخول') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
