<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight tracking-tight">
            {{ __('قائمة الإشعارات') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-xl rounded-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-slate-700">
                        {{ __('جميع الإشعارات') }}
                    </h3>
                    <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out transform hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 rtl:ml-2 rtl:mr-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('تحديد الكل كمقروء') }}
                        </button>
                    </form>
                </div>

                @forelse($notifications as $notification)
                    <div class="flex items-start justify-between p-4 mb-3 rounded-lg transition-all duration-200 ease-in-out
                                {{ $notification->is_read ? 'bg-slate-50 text-slate-600 hover:bg-slate-100' : 'bg-blue-50 text-blue-800 border border-blue-200 hover:bg-blue-100 shadow-sm' }}">
                        <div class="flex-grow mr-4 rtl:ml-4 rtl:mr-0">
                            <div class="font-bold text-lg {{ $notification->is_read ? 'text-slate-800' : 'text-blue-900' }}">{{ $notification->title }}</div>
                            <div class="text-sm {{ $notification->is_read ? 'text-slate-600' : 'text-blue-700' }} mt-1">{{ $notification->body }}</div>
                        </div>
                        <div class="flex-shrink-0 text-left rtl:text-right">
                            <span class="text-xs {{ $notification->is_read ? 'text-slate-500' : 'text-blue-600' }}">{{ $notification->created_at->diffForHumans() }}</span>
                            @if(!$notification->is_read)
                                <span class="ml-2 rtl:mr-2 rtl:ml-0 text-xs bg-red-500 text-white px-2 py-0.5 rounded-full font-bold">{{ __('جديد') }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-slate-500">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <p>{{ __('لا توجد إشعارات حالياً.') }}</p>
                        </div>
                    </div>
                @endforelse

                {{-- روابط التصفح --}}
                @if ($notifications->hasPages())
                    <div class="mt-8 p-4 bg-slate-50 rounded-lg shadow-sm">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
