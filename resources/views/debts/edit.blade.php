<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('تعديل الدين') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">
                <form action="{{ route('debts.update', $debt->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="client_id" class="block text-sm font-medium text-slate-700 mb-2">{{ __('الزبون') }}</label>
                        <select name="client_id" id="client_id" class="block w-full px-4 py-2 border border-slate-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm" required>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $debt->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-2">{{ __('الوصف') }}</label>
                        <input type="text" name="description" id="description" value="{{ $debt->description }}" class="block w-full px-4 py-2 border border-slate-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm" required placeholder="{{ __('ادخل وصفاً للدين') }}">
                    </div>

                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-slate-700 mb-2">{{ __('المبلغ') }}</label>
                        <input type="number" name="amount" id="amount" step="0.01" value="{{ $debt->amount }}" class="block w-full px-4 py-2 border border-slate-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm" required placeholder="{{ __('0.00') }}">
                    </div>

                    <div class="mb-6">
                        <label for="debt_date" class="block text-sm font-medium text-slate-700 mb-2">{{ __('تاريخ الدين') }}</label>
                        <input type="date" name="debt_date" id="debt_date" class="block w-full px-4 py-2 border border-slate-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm" value="{{ $debt->debt_date }}" required>
                    </div>

                    <div class="mt-8 flex justify-start rtl:justify-end space-x-4 rtl:space-x-reverse">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            {{ __('تحديث الدين') }}
                        </button>
                        <a href="{{ route('debts.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('إلغاء') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
