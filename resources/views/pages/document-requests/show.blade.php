<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Progres Permintaan: {{ $documentRequest->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('document-requests.index') }}"
                        class="text-blue-600 hover:text-blue-800 font-semibold mb-4 inline-block">&larr; Kembali ke
                        Daftar Permintaan</a>

                    <div class="border-t border-gray-200 py-4">
                        <p><span class="font-semibold">Dibuat oleh:</span> {{ $documentRequest->creator->name }}</p>
                        <p><span class="font-semibold">Batas Waktu:</span>
                            {{ $documentRequest->due_date ? \Carbon\Carbon::parse($documentRequest->due_date)->format('d M Y') : '-' }}
                        </p>
                        <p class="mt-2"><span
                                class="font-semibold">Deskripsi:</span><br>{{ $documentRequest->description ?? '-' }}
                        </p>
                    </div>

                    <hr class="my-4">

                    <h3 class="text-lg font-medium mb-4">Progres Pengumpulan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4">Nama Entitas</th>
                                    <th class="text-left py-3 px-4">Tipe</th>
                                    <th class="text-center py-3 px-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($documentRequest->entities as $entity)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        {{-- Nama entitas menjadi link jika sudah ada unggahan --}}
                                        @if(isset($submissionStatuses[$entity->id]) && $submissionStatuses[$entity->id]
                                        !== 'Belum Mengunggah')
                                        <a href="{{ route('document-requests.show-entity-uploads', [$documentRequest->id, $entity->id]) }}"
                                            class="text-blue-600 hover:text-blue-800 font-semibold">
                                            {{ $entity->name }}
                                        </a>
                                        @else
                                        {{ $entity->name }}
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">{{ $entity->type }}</td>
                                    <td class="text-center py-3 px-4">
                                        @php
                                        $status = $submissionStatuses[$entity->id] ?? 'Belum Mengunggah';
                                        $class = 'bg-gray-100 text-gray-800'; // Default
                                        if ($status == 'Diterima') $class = 'bg-green-100 text-green-800';
                                        if ($status == 'Perlu Direview') $class = 'bg-yellow-100 text-yellow-800';
                                        if ($status == 'Revisi Diperlukan') $class = 'bg-red-100 text-red-800';
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">Tidak ada entitas yang ditugaskan untuk
                                        permintaan ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>