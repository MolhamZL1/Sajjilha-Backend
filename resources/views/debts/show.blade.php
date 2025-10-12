<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('تفاصيل الدين') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-slate-700">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">{{ __('الزبون') }}</p>
                        <p class="text-lg font-medium text-slate-800">{{ $debt->client->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">{{ __('المبلغ') }}</p>
                        <p class="text-lg font-bold text-red-600">{{ number_format($debt->amount, 2) }} {{ __('ريال') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-semibold text-slate-500">{{ __('الوصف') }}</p>
                        <p class="text-lg font-medium text-slate-700">{{ $debt->description ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">{{ __('تاريخ الدين') }}</p>
                        <p class="text-lg font-medium text-slate-700">{{ $debt->debt_date }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">{{ __('تاريخ الإنشاء') }}</p>
                        <p class="text-lg font-medium text-slate-700">{{ $debt->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-500">{{ __('آخر تحديث') }}</p>
                        <p class="text-lg font-medium text-slate-700">{{ $debt->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-start rtl:justify-end space-x-4 rtl:space-x-reverse">
                    <a href="{{ route('debts.edit', $debt->id) }}"
                       class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                        {{ __('تعديل الدين') }}
                    </a>
                    <a href="{{ route('debts.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('العودة إلى قائمة الديون') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
