<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    {{-- أضف مكتبة Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ترحيب --}}
            <div class="bg-gradient-to-r from-sky-600 to-indigo-700 text-white overflow-hidden shadow-xl rounded-xl p-8 text-center">
                <h1 class="text-3xl font-bold mb-2">{{ __("أهلاً بك في لوحة التحكم!") }}</h1>
                <p class="text-lg opacity-90">{{ __("نظرة عامة شاملة على حساباتك وأنشطتك الرئيسية.") }}</p>
            </div>

            {{-- إحصائيات الحساب --}}
            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">
                <h3 class="text-xl font-semibold text-slate-700 mb-5 border-l-4 border-sky-500 pl-3 rtl:border-l-0 rtl:border-r-4 rtl:pr-3 rtl:pl-0">ملخص الحساب</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-center">
                    <div class="p-6 bg-slate-50 rounded-xl shadow-lg border border-slate-200 hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm text-slate-500 uppercase tracking-wider">إجمالي الديون</p>
                        <p class="text-3xl font-bold text-red-500 mt-2">{{ number_format($total_debts, 2) }}</p>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-xl shadow-lg border border-slate-200 hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm text-slate-500 uppercase tracking-wider">إجمالي التسديدات</p>
                        <p class="text-3xl font-bold text-green-500 mt-2">{{ number_format($total_payments, 2) }}</p>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-xl shadow-lg border border-slate-200 hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm text-slate-500 uppercase tracking-wider">الرصيد المتبقي</p>
                        <p class="text-3xl font-bold text-sky-600 mt-2">{{ number_format($balance, 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- إحصائيات العملاء --}}
            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">
                <h3 class="text-xl font-semibold text-slate-700 mb-5 border-l-4 border-sky-500 pl-3 rtl:border-l-0 rtl:border-r-4 rtl:pr-3 rtl:pl-0">إحصائيات العملاء</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-center mb-8">
                    <div class="p-6 bg-slate-50 rounded-xl shadow-lg border border-slate-200 hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm text-slate-500 uppercase tracking-wider">عدد العملاء</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $clients_count }}</p>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-xl shadow-lg border border-slate-200 hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm text-slate-500 uppercase tracking-wider">عملاء مدينون</p>
                        <p class="text-3xl font-bold text-red-500 mt-2">{{ $clients_in_debt }}</p>
                    </div>
                    <div class="p-6 bg-slate-50 rounded-xl shadow-lg border border-slate-200 hover:shadow-xl transition-shadow duration-300">
                        <p class="text-sm text-slate-500 uppercase tracking-wider">عملاء مسددون</p>
                        <p class="text-3xl font-bold text-green-500 mt-2">{{ $clients_clear }}</p>
                    </div>
                </div>

                {{-- المخطط البياني للعملاء --}}
                <div class="bg-slate-50 shadow-lg rounded-xl p-6 border border-slate-200 max-w-lg mx-auto hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold text-slate-700 mb-4 text-center">نسبة العملاء المدينين مقابل المسددين</h3>
                    <canvas id="clientsPieChart" class="max-h-80"></canvas>
                </div>
            </div>

            {{-- آخر الديون --}}
            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">
                <h3 class="text-xl font-semibold text-slate-700 mb-5 border-l-4 border-sky-500 pl-3 rtl:border-l-0 rtl:border-r-4 rtl:pr-3 rtl:pl-0">أحدث 10 ديون</h3>
                <ul class="space-y-2">
                    @forelse($debts as $debt)
                        <li class="py-3 px-4 even:bg-slate-50 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center hover:bg-sky-50 transition-colors duration-150 ease-in-out border border-transparent hover:border-sky-200">
                            <div>
                                <span class="font-medium text-slate-800">{{ $debt->client->name }}</span>
                                <span class="text-xs text-slate-500 block sm:inline sm:ml-2 rtl:sm:mr-2 rtl:sm:ml-0"> ({{ $debt->created_at->format('Y-m-d') }})</span>
                            </div>
                            <span class="font-semibold text-red-500 text-lg mt-1 sm:mt-0">{{ number_format($debt->amount, 2) }} ريال</span>
                        </li>
                    @empty
                        <li class="text-slate-500 p-4 text-center bg-slate-50 rounded-lg">لا توجد ديون مسجلة.</li>
                    @endforelse
                </ul>
            </div>

            {{-- آخر التسديدات --}}
            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">
                <h3 class="text-xl font-semibold text-slate-700 mb-5 border-l-4 border-sky-500 pl-3 rtl:border-l-0 rtl:border-r-4 rtl:pr-3 rtl:pl-0">أحدث 10 تسديدات</h3>
                <ul class="space-y-2">
                    @forelse($payments as $payment)
                        <li class="py-3 px-4 even:bg-slate-50 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center hover:bg-green-50 transition-colors duration-150 ease-in-out border border-transparent hover:border-green-200">
                            <div>
                                <span class="font-medium text-slate-800">{{ $payment->client->name }}</span>
                                <span class="text-xs text-slate-500 block sm:inline sm:ml-2 rtl:sm:mr-2 rtl:sm:ml-0"> ({{ $payment->created_at->format('Y-m-d') }})</span>
                            </div>
                            <span class="font-semibold text-green-500 text-lg mt-1 sm:mt-0">{{ number_format($payment->amount, 2) }} ريال</span>
                        </li>
                    @empty
                        <li class="text-slate-500 p-4 text-center bg-slate-50 rounded-lg">لا توجد تسديدات مسجلة.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>

    {{-- كود مخطط Chart.js --}}
    <script>
        const ctx = document.getElementById('clientsPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['مدينون', 'مسددون'],
                datasets: [{
                    label: 'حالة العملاء',
                    data: [{{ $clients_in_debt }}, {{ $clients_clear }}],
                    backgroundColor: [
                        '#F87171', // Tailwind red-400
                        '#34D399'  // Tailwind green-400
                    ],
                    borderColor: [
                        '#FCA5A5', // Tailwind red-300
                        '#6EE7B7'  // Tailwind green-300
                    ],
                    borderWidth: 1,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 14,
                                family: "Figtree, sans-serif" // Changed to Figtree for consistency
                            },
                            color: '#374151', // text-slate-700
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#374151',
                        bodyColor: '#4B5563',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 6,
                        bodyFont: {
                            family: "Figtree, sans-serif" // Changed to Figtree for consistency
                        },
                        titleFont: {
                            family: "Figtree, sans-serif", // Changed to Figtree for consistency
                            weight: 'bold'
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>

</x-app-layout>
