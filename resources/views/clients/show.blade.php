<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
                {{ __('ملف العميل:') }} <span class="text-sky-600">{{ $client->name }}</span>
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                <div class="p-8 space-y-6">
                    {{-- معلومات العميل الأساسية --}}
                    <div>
                        <h3 class="text-lg font-semibold text-slate-700 mb-1">{{ __('الاسم الكامل:') }}</h3>
                        <p class="text-slate-900 text-xl">{{ $client->name }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">{{ __('رقم الهاتف:') }}</h3>
                            <p class="text-slate-700">{{ $client->phone ?: __('لم يتم تسجيل رقم هاتف') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">{{ __('العنوان:') }}</h3>
                            <p class="text-slate-700">{{ $client->address ?: __('لم يتم تسجيل عنوان') }}</p>
                        </div>
                    </div>

                    {{-- الملخص المالي --}}
                    <div class="pt-6 border-t border-slate-200">
                        <h3 class="text-xl font-semibold text-slate-700 mb-4">{{ __('الملخص المالي') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-center">
                            <div class="p-6 bg-red-50 rounded-xl shadow-lg border border-red-200">
                                <p class="text-sm text-red-500 uppercase tracking-wider">{{ __('إجمالي الديون') }}</p>
                                <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($client->total_debt, 2) }} {{ __('ريال') }}</p>
                            </div>
                            <div class="p-6 bg-green-50 rounded-xl shadow-lg border border-green-200">
                                <p class="text-sm text-green-500 uppercase tracking-wider">{{ __('إجمالي التسديدات') }}</p>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($client->total_paid, 2) }} {{ __('ريال') }}</p>
                            </div>
                            <div class="p-6 {{ $client->balance > 0 ? 'bg-red-50 border-red-200' : ($client->balance < 0 ? 'bg-green-50 border-green-200' : 'bg-slate-50 border-slate-200') }} rounded-xl shadow-lg border">
                                <p class="text-sm {{ $client->balance > 0 ? 'text-red-500' : ($client->balance < 0 ? 'text-green-500' : 'text-slate-500') }} uppercase tracking-wider">{{ __('الرصيد المتبقي') }}</p>
                                <p class="text-3xl font-bold {{ $client->balance > 0 ? 'text-red-600' : ($client->balance < 0 ? 'text-green-600' : 'text-slate-700') }} mt-2">{{ number_format($client->balance, 2) }} {{ __('ريال') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- أزرار إجراءات إضافية --}}
                    <div class="pt-8 flex flex-col sm:flex-row justify-center sm:justify-start gap-3 border-t border-slate-200">
                        <a href="{{ route('clients.statement', $client->id) }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                            {{ __('عرض كشف الحساب') }}
                        </a>
                        <a href="{{ route('clients.edit', $client->id) }}"
                           class="inline-flex items-center justify-center px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                            {{ __('تعديل بيانات العميل') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
