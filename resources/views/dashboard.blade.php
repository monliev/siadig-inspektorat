<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Judul akan dinamis, jika admin maka akan menampilkan "Dashboard Administrator" --}}
            @can('isAdmin')
                {{ __('Dashboard Administrator') }}
            @else
                {{ __('Dashboard') }}
            @endcan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ============================================= --}}
            {{--    BAGIAN STATISTIK (HANYA UNTUK ADMIN)      --}}
            {{-- ============================================= --}}
            @can('isAdmin')
            <div class="mb-8">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Ringkasan Arsip Internal</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-medium text-gray-700">Total Arsip Internal</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $internalDocsCount ?? 0 }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-medium text-gray-700">Total Kategori Internal</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $internalCatsCount ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-100 border-l-4 border-yellow-400 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-medium text-yellow-800">Internal Menunggu Review</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $internalReviewCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Ringkasan Dokumen Klien</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                     <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-medium text-gray-700">Total Permintaan Dibuat</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $totalRequestsCount ?? 0 }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-medium text-gray-700">Total Dokumen Diterima</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $externalDocsCount ?? 0 }}</p>
                    </div>
                    <div class="bg-teal-100 border-l-4 border-teal-400 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-medium text-teal-800">Klien Menunggu Review</h3>
                        <p class="mt-1 text-3xl font-semibold">{{ $externalReviewCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
            @endcan

            {{-- ============================================= --}}
            {{--    KONTEN UTAMA (BERBEDA UNTUK TIAP PERAN)    --}}
            {{-- ============================================= --}}
            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Arsip Internal Terbaru</h3>
                        <div class="space-y-4">
                            @forelse ($recentInternalDocuments as $document)
                                <div class="border-b pb-2">
                                    <p class="font-semibold text-gray-800">{{ $document->title }}</p>
                                    <div class="flex justify-between text-sm text-gray-500 mt-1">
                                        <span>Kategori: <span class="font-medium text-gray-700">{{ $document->category->name ?? 'N/A' }}</span></span>
                                        <span>Diunggah: <span class="font-medium text-gray-700">{{ $document->created_at->diffForHumans() }}</span></span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-500">Belum ada arsip internal yang diunggah.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                     <div class="p-6">
                        @can('isAdmin')
                            {{-- Jika Admin, tampilkan log semua user --}}
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Log Aktivitas Terbaru (Semua Pengguna)</h3>
                            <div class="space-y-4">
                                @forelse ($allActivities as $activity)
                                    <div class="border-b pb-2">
                                        <p class="font-semibold text-gray-800">{{ $activity->description }}</p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            oleh {{ $activity->user->name ?? 'Sistem' }} - {{ $activity->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500">Belum ada aktivitas tercatat.</p>
                                @endforelse
                            </div>
                        @else
                            {{-- Jika bukan Admin, tampilkan log pribadi --}}
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Aktivitas Terakhir Anda</h3>
                            <div class="space-y-4">
                                @forelse ($userActivities as $activity)
                                     <div class="border-b pb-2">
                                        <p class="font-semibold text-gray-800">{{ $activity->description }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                @empty
                                    <p class="text-center text-gray-500">Belum ada aktivitas tercatat.</p>
                                @endforelse
                            </div>
                        @endcan
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>