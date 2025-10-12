<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('قائمة الديون') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">
                {{-- زر إضافة دين جديد --}}
                <div class="mb-6 flex justify-start rtl:justify-end">
                    <a href="{{ route('debts.create') }}"
                       class="inline-flex items-center px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        {{ __('إضافة دين جديد') }}
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

                {{-- جدول الديون --}}
                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                        <thead class="bg-slate-100">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('الزبون') }}</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('الوصف') }}</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('المبلغ') }}</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider">{{ __('تاريخ الدين') }}</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-slate-600 uppercase tracking-wider text-center">{{ __('الإجراءات') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($debts as $debt)
                            <tr class="hover:bg-slate-50 transition-colors duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-slate-800 font-medium">{{ $debt->client->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $debt->description ?: '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-red-600">{{ number_format($debt->amount, 2) }} {{ __('ريال') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">{{ $debt->debt_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                                        <a href="{{ route('debts.show', $debt->id) }}" class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 rounded-full transition-colors duration-150" title="{{ __('عرض التفاصيل') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                        </a>
                                        <a href="{{ route('debts.edit', $debt->id) }}" class="p-2 text-amber-500 hover:text-amber-700 hover:bg-amber-100 rounded-full transition-colors duration-150" title="{{ __('تعديل') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                        </a>
                                        <form action="{{ route('debts.destroy', $debt->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('هل أنت متأكد من حذف هذا الدين؟') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-100 rounded-full transition-colors duration-150" title="{{ __('حذف') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l2-2m0 0l2-2m-2 2L9 10m-2 2l2 2m2 2l2-2m2-2L14 9l-2 2M5 11h14a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a2 2 0 012-2z" />
                                        </svg>
                                        {{ __('لا يوجد ديون مسجلة حالياً.') }}
                                        <a href="{{ route('debts.create') }}" class="mt-2 text-sky-600 hover:text-sky-700 font-medium">{{ __('أضف دين جديد الآن') }}</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- روابط التصفح --}}
                @if ($debts->hasPages())
                    <div class="mt-8 p-4 bg-slate-50 rounded-lg shadow-sm">
                        {{ $debts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
