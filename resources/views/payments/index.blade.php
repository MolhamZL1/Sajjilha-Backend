<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('قائمة الدفعات') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">

                {{-- زر إضافة دفعة جديدة --}}
                <div class="mb-6 flex justify-start rtl:justify-end">
                    <a href="{{ route('payments.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        {{ __('إضافة دفعة جديدة') }}
                    </a>
                </div>

                {{-- رسالة نجاح --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 border-l-4 border-green-500 rounded-md shadow-sm rtl:border-l-0 rtl:border-r-4" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-green-500 mr-4 rtl:ml-4 rtl:mr-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold">{{ __('نجاح!') }}</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- جدول الدفعات --}}
                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                        <thead class="bg-slate-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('الزبون') }}</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('المبلغ') }}</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('تاريخ الدفع') }}</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('ملاحظات') }}</th>
                            {{-- يمكنك إضافة عمود للإجراءات إذا لزم الأمر --}}
                            {{-- <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('إجراءات') }}</th> --}}
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50 transition-colors duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $payment->client->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-green-600 font-bold">{{ number_format($payment->amount, 2) }} {{ __('ريال') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 max-w-xs truncate" title="{{ $payment->notes }}">{{ $payment->notes ?: '-' }}</td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                </td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        {{ __('لا توجد دفعات مسجلة حالياً.') }}
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- روابط التصفح --}}
                @if ($payments->hasPages())
                    <div class="mt-8 p-4 bg-slate-50 rounded-lg shadow-sm">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
