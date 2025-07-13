<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Dokumen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium mb-4">Daftar Dokumen Menunggu Persetujuan</h3>

                    @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Judul</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kategori</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Pengunggah</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($reviewDocuments as $document)
                                <tr class="border-b">
                                    <td class="text-left py-3 px-4">{{ $document->title }}</td>
                                    <td class="text-left py-3 px-4">{{ $document->category->name ?? '[Dihapus]' }}</td>
                                    <td class="text-left py-3 px-4">{{ $document->uploader->name ?? '[Dihapus]' }}</td>
                                    <td class="text-center py-3 px-4 flex items-center justify-center space-x-2">
                                        {{-- Tombol Approve --}}
                                        <form action="{{ route('documents.approve', $document->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menyetujui dokumen ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-sm bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded">Approve</button>
                                        </form>
                                        {{-- Tombol Reject --}}
                                        <form action="{{ route('documents.reject', $document->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menolak dokumen ini?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded">Reject</button>
                                        </form>
                                        {{-- Link Detail --}}
                                        <a href="{{ route('documents.show', $document->id) }}"
                                            class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 px-4">
                                        Tidak ada dokumen yang perlu direview.
                                    </td>
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