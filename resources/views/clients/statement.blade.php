<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
                {{ __('كشف حساب العميل:') }} <span class="text-sky-600">{{ $client['name'] }}</span>
            </h2>
            <a href="{{ route('clients.index') }}"
               class="mt-2 sm:mt-0 inline-flex items-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg shadow-sm transition-colors duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 rtl:ml-2 rtl:mr-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                {{ __('العودة إلى قائمة العملاء') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-8">
                {{-- معلومات العميل --}}
                <div class="pb-6 border-b border-slate-200">
                    <h3 class="text-xl font-semibold text-slate-700 mb-3">{{ __('معلومات العميل') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3">
                        <p><strong class="text-slate-500">{{ __('الاسم:') }}</strong> <span class="text-slate-800">{{ $client['name'] }}</span></p>
                        <p><strong class="text-slate-500">{{ __('الهاتف:') }}</strong> <span class="text-slate-800">{{ $client['phone'] ?: __('غير متوفر') }}</span></p>
                    </div>
                </div>

                {{-- الملخص المالي --}}
                <div class="py-6 border-b border-slate-200">
                    <h3 class="text-xl font-semibold text-slate-700 mb-4">{{ __('الملخص المالي') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="p-5 bg-red-50 rounded-lg border border-red-200">
                            <p class="text-sm text-red-500 uppercase tracking-wider">{{ __('إجمالي الديون') }}</p>
                            <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format(floatval($total_debt), 2) }} {{ __('ريال') }}</p>
                        </div>
                        <div class="p-5 bg-green-50 rounded-lg border border-green-200">
                            <p class="text-sm text-green-500 uppercase tracking-wider">{{ __('إجمالي التسديدات') }}</p>
                            <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format(floatval($total_paid), 2) }} {{ __('ريال') }}</p>
                        </div>
                        @php
                            $remaining_float = floatval($remaining);
                            $remaining_color_class = $remaining_float > 0 ? 'text-red-600 bg-red-50 border-red-200' : ($remaining_float < 0 ? 'text-green-600 bg-green-50 border-green-200' : 'text-slate-700 bg-slate-50 border-slate-200');
                            $remaining_text_color = $remaining_float > 0 ? 'text-red-600' : ($remaining_float < 0 ? 'text-green-600' : 'text-slate-700');
                            $remaining_title_color = $remaining_float > 0 ? 'text-red-500' : ($remaining_float < 0 ? 'text-green-500' : 'text-slate-500');
                        @endphp
                        <div class="p-5 rounded-lg border {{ $remaining_color_class }}">
                            <p class="text-sm uppercase tracking-wider {{ $remaining_title_color }}">{{ __('الرصيد المتبقي') }}</p>
                            <p class="text-2xl font-bold mt-1 {{ $remaining_text_color }}">{{ number_format($remaining_float, 2) }} {{ __('ريال') }}</p>
                        </div>
                    </div>
                </div>

                {{-- تفاصيل الحركات --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-6">
                    {{-- الديون --}}
                    <div>
                        <h3 class="text-xl font-semibold text-slate-700 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 rtl:ml-2 rtl:mr-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l-4-4m0 0l4-4m-4 4h12a2 2 0 012 2v2M9 14l4-4m-4 4H5m14-4v10a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2h3.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H19a2 2 0 012 2v.5" />
                            </svg>
                            {{ __('قائمة الديون') }}
                        </h3>
                        @if($debts->count() > 0)
                            <ul class="space-y-3">
                                @foreach($debts as $debt)
                                    <li class="p-4 bg-slate-50 rounded-lg border border-slate-200 hover:border-red-300 hover:bg-red-50 transition-colors duration-150">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-red-600">{{ number_format($debt->amount, 2) }} {{ __('ريال') }}</span>
                                            <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($debt->created_at)->format('Y-m-d H:i') }}</span>
                                        </div>
                                        @if($debt->description)
                                            <p class="text-sm text-slate-600 mt-1">{{ $debt->description }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-slate-500 p-4 bg-slate-50 rounded-lg border border-slate-200 text-center">{{ __('لا توجد ديون مسجلة لهذا العميل.') }}</p>
                        @endif
                    </div>

                    {{-- التسديدات --}}
                    <div>
                        <h3 class="text-xl font-semibold text-slate-700 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 rtl:ml-2 rtl:mr-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 14l-4 4m0 0l-4-4m4 4V3M15 14H9m12-4v10a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2h3.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H19a2 2 0 012 2v.5" />
                            </svg>
                            {{ __('قائمة التسديدات') }}
                        </h3>
                        @if($payments->count() > 0)
                            <ul class="space-y-3">
                                @foreach($payments as $payment)
                                    <li class="p-4 bg-slate-50 rounded-lg border border-slate-200 hover:border-green-300 hover:bg-green-50 transition-colors duration-150">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-green-600">{{ number_format($payment->amount, 2) }} {{ __('ريال') }}</span>
                                            <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($payment->payment_date ?: $payment->created_at)->format('Y-m-d H:i') }}</span>
                                        </div>
                                        @if($payment->notes)
                                            <p class="text-sm text-slate-600 mt-1">{{ $payment->notes }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-slate-500 p-4 bg-slate-50 rounded-lg border border-slate-200 text-center">{{ __('لا توجد تسديدات مسجلة لهذا العميل.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
