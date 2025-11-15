<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ isset($client) ? __('تعديل بيانات العميل') : __('إضافة عميل جديد') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-8">
                <form method="POST" action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}" class="space-y-6">
                    @csrf
                    @if(isset($client))
                        @method('PUT')
                    @endif

                    {{-- الاسم --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('الاسم الكامل للعميل') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $client->name ?? '') }}" required
                               class="block w-full mt-1 rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-right"
                               placeholder="{{ __('مثال: محمد عبدالله') }}">
                        @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- الهاتف --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('رقم الهاتف') }}
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $client->phone ?? '') }}"
                               class="block w-full mt-1 rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-right"
                               placeholder="{{ __('مثال: 05xxxxxxxx') }}">
                        @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- العنوان --}}
                    <div>
                        <label for="address" class="block text-sm font-medium text-slate-700 mb-1">
                            {{ __('العنوان') }}
                        </label>
                        <textarea id="address" name="address" rows="3"
                                  class="block w-full mt-1 rounded-lg border-slate-300 shadow-sm focus:border-sky-500 focus:ring focus:ring-sky-200 focus:ring-opacity-50 text-right"
                                  placeholder="{{ __('مثال: الرياض، حي الملز، شارع الأمير فهد') }}">{{ old('address', $client->address ?? '') }}</textarea>
                        @error('address')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- الأزرار --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-4 border-t border-slate-200 mt-8">
                        <a href="{{ route('clients.index') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out order-2 sm:order-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                            </svg>
                            {{ __('إلغاء') }}
                        </a>

                        <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 {{ isset($client) ? 'bg-amber-500 hover:bg-amber-600' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out transform hover:-translate-y-0.5 order-1 sm:order-2">
                            @if(isset($client))
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                {{ __('تحديث البيانات') }}
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ __('حفظ العميل') }}
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
