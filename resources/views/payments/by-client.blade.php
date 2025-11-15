<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('تسديدات الزبون:') }} <span class="text-sky-600">{{ $client->name ?? __('غير معروف') }}</span>
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- زر إضافة تسديدة و زر الرجوع --}}
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('clients.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                    </svg>
                    {{ __('رجوع إلى قائمة العملاء') }}
                </a>
                <a href="{{ route('payments.create', ['client_id' => $client->id]) }}"
                   class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ __('إضافة تسديدة جديدة لهذا الزبون') }}
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">
                <h3 class="text-xl font-semibold text-slate-700 mb-5 border-l-4 border-sky-500 pl-3 rtl:border-l-0 rtl:border-r-4 rtl:pr-3 rtl:pl-0">
                    {{ __('قائمة التسديدات') }}
                </h3>

                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                        <thead class="bg-slate-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('المبلغ') }}</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('تاريخ التسديد') }}</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('ملاحظات') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50 transition-colors duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-green-600 font-bold">{{ number_format($payment->amount, 2) }} {{ __('د.ل') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 max-w-xs truncate" title="{{ $payment->notes }}">{{ $payment->notes ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L3 10.25m0 0l6.75-6.75M3 10.25h18" />
                                        </svg>
                                        {{ __('لا توجد تسديدات مسجلة لهذا الزبون.') }}
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
