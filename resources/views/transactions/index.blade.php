<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('كل الحركات المالية') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8"> {{-- Increased vertical padding --}}
        <div class="bg-white shadow-xl rounded-xl p-6 border border-slate-200"> {{-- Enhanced card styling --}}

            {{-- Filter Form --}}
            <form method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end"> {{-- Use grid for better responsiveness --}}
                {{-- Client Name Search --}}
                <div>
                    <label for="search" class="block text-sm font-medium text-slate-700 mb-1">{{ __('اسم العميل') }}</label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        placeholder="{{ __('ابحث باسم العميل...') }}"
                        value="{{ $filters['search'] ?? '' }}"
                        class="block w-full border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm"
                    >
                </div>

                {{-- Type Filter --}}
                <div>
                    <label for="type" class="block text-sm font-medium text-slate-700 mb-1">{{ __('النوع') }}</label>
                    <select
                        name="type"
                        id="type"
                        class="block w-full border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm"
                    >
                        <option value="">{{ __('الكل') }}</option>
                        <option value="debt" {{ ($filters['type'] ?? '') == 'debt' ? 'selected' : '' }}>{{ __('دين') }}</option>
                        <option value="payment" {{ ($filters['type'] ?? '') == 'payment' ? 'selected' : '' }}>{{ __('تسديدة') }}</option>
                    </select>
                </div>

                {{-- From Date Filter --}}
                <div>
                    <label for="from_date" class="block text-sm font-medium text-slate-700 mb-1">{{ __('من تاريخ') }}</label>
                    <input
                        type="date"
                        name="from_date"
                        id="from_date"
                        value="{{ $filters['fromDate'] ?? '' }}"
                        class="block w-full border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm"
                    >
                </div>

                {{-- To Date Filter --}}
                <div>
                    <label for="to_date" class="block text-sm font-medium text-slate-700 mb-1">{{ __('إلى تاريخ') }}</label>
                    <input
                        type="date"
                        name="to_date"
                        id="to_date"
                        value="{{ $filters['toDate'] ?? '' }}"
                        class="block w-full border-slate-300 focus:border-sky-500 focus:ring-sky-500 rounded-md shadow-sm text-sm"
                    >
                </div>

                {{-- Filter Button --}}
                <div class="md:col-span-2 lg:col-span-1"> {{-- Button takes full width on small screens, single column on larger --}}
                    <button
                        type="submit"
                        class="w-full bg-sky-600 text-white px-4 py-2 rounded-md hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 shadow-sm transition-all duration-150 ease-in-out transform hover:-translate-y-0.5"
                    >
                        {{ __('تطبيق الفلتر') }}
                    </button>
                </div>
            </form>

            {{-- Transactions Table --}}
            <div class="overflow-x-auto"> {{-- Added overflow for small screens --}}
                <table class="min-w-full divide-y divide-slate-200 text-right"> {{-- Changed gray to slate --}}
                    <thead>
                    <tr class="bg-slate-100"> {{-- Changed gray to slate --}}
                        <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider text-right rounded-tr-lg rtl:rounded-tl-lg rtl:rounded-tr-none">{{ __('العميل') }}</th> {{-- Added specific rounding and text styling --}}
                        <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider text-right">{{ __('النوع') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider text-right">{{ __('المبلغ') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider text-right">{{ __('الوصف') }}</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider text-right rounded-tl-lg rtl:rounded-tr-lg rtl:rounded-tl-none">{{ __('التاريخ') }}</th> {{-- Added specific rounding and text styling --}}
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200"> {{-- Changed gray to slate --}}
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50 transition-colors duration-150"> {{-- Added hover effect --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-800">{{ $t->client->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold leading-tight
                                        {{ $t->type == 'debt' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $t->type == 'debt' ? __('دين') : __('تسديدة') }}
                                    </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-{{ $t->type == 'debt' ? 'red' : 'green' }}-600">
                                {{ number_format($t->amount, 2) }} ريال
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $t->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $t->date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-slate-500 bg-slate-50 rounded-b-lg"> {{-- Enhanced empty state --}}
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('لا توجد حركات مالية مطابقة للمعايير المحددة.') }}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Optional: Pagination if you implement it --}}
            {{-- <div class="mt-6">
                {{ $transactions->links() }}
            </div> --}}
        </div>
    </div>
</x-app-layout>
