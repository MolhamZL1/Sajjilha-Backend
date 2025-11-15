<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('إضافة دفعة جديدة') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-8">
                <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- الزبون --}}
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('الزبون') }} <span class="text-red-500">*</span>
                        </label>
                        <select id="client_id" name="client_id" required
                                class="block w-full mt-1 rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-right">
                            <option value="" disabled selected>{{ __('اختر الزبون') }}</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- المبلغ --}}
                    <div>
                        <label for="amount" class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('المبلغ') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" required
                               class="block w-full mt-1 rounded-lg border-slate-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 text-right"
                               placeholder="{{ __('أدخل المبلغ') }}">
                        @error('amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- تاريخ الدفع (اختياري، يمكن جعله إلزاميًا إذا لزم الأمر) --}}
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('تاريخ الدفع') }}
                        </label>
                        <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}"
                               class="block w-full mt-1 rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-right">
                        @error('payment_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ملاحظات --}}
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('ملاحظات') }}
                        </label>
                        <textarea id="notes" name="notes" rows="4"
                                  class="block w-full mt-1 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-right"
                                  placeholder="{{ __('أضف أي ملاحظات إضافية هنا...') }}">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- الأزرار --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-4 border-t border-slate-200 mt-8">
                        <a href="{{ route('payments.index') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out order-2 sm:order-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                            </svg>
                            {{ __('رجوع') }}
                        </a>

                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out transform hover:-translate-y-0.5 order-1 sm:order-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{ __('حفظ الدفعة') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
