<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h2 class="text-2xl font-bold text-center text-slate-800 mb-8">{{ __('إنشاء حساب جديد') }}</h2>

        <div class="mb-4">
            <x-input-label for="name" :value="__('الاسم')" />
            <x-text-input
                id="name"
                class="block mt-1 w-full border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600" />
        </div>

        <div class="mb-4">
            <x-input-label for="mobile" :value="__('رقم الجوال')" />
            <x-text-input
                id="mobile"
                class="block mt-1 w-full border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm"
                type="text"
                name="mobile"
                :value="old('mobile')"
                required
                autocomplete="tel"
            />
            <x-input-error :messages="$errors->get('mobile')" class="mt-2 text-red-600" />
        </div>

        <div class="mb-4">
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm"
                type="email"
                name="email"
                :value="old('email')"
                required
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
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600" />
        </div>

        <div class="mb-6"> {{-- Increased bottom margin for this section --}}
            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600" />
        </div>

        <div class="flex items-center justify-end">
            <a
                class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition-colors duration-150" {{-- Changed color to sky --}}
            href="{{ route('login') }}"
            >
                {{ __('هل لديك حساب بالفعل؟') }} {{-- Translated --}}
            </a>

            <x-primary-button class="ms-4 bg-sky-600 hover:bg-sky-700 focus:ring-sky-500 transition-all duration-150 transform hover:-translate-y-0.5"> {{-- Changed button color --}}
                {{ __('تسجيل') }} {{-- Translated --}}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
