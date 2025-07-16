<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Semua Notifikasi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-4">
                        @forelse ($notifications as $notification)
                            <a href="{{ data_get($notification->data, 'url', '#') }}?read={{ $notification->id }}" 
                               class="block p-4 rounded-lg transition duration-300 {{ $notification->read() ? 'bg-gray-50 text-gray-500' : 'bg-blue-50 hover:bg-blue-100 border border-blue-200' }}">

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 pt-1">
                                        @if(!$notification->read())
                                            <span class="relative flex h-3 w-3">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span>
                                            </span>
                                        @else
                                             <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12.75l3 3m3-3l3-3m-3 3v6m0 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @endif
                                    </div>
                                    <div class="ms-4 flex-grow">
                                        <p class="font-semibold">
                                            {{-- Logika untuk menampilkan nama pengirim dari berbagai jenis notifikasi --}}
                                            {{ $notification->data['from_user_name'] ?? ($notification->data['applicant_name'] ?? 'Sistem') }}
                                        </p>
                                        <p class="text-sm">{{ $notification->data['message'] ?? 'Anda memiliki notifikasi baru.' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-gray-500">Tidak ada notifikasi untuk ditampilkan.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>