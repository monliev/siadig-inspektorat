<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Arsip Dokumen Internal') }}
            </h2>

            {{-- PINDAHKAN TOMBOL KE SINI --}}
            <a href="{{ route('documents.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                + Unggah Dokumen
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-4">
                        <form action="{{ route('documents.index') }}" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                {{-- Filter Kata Kunci --}}
                                <div class="md:col-span-2">
                                    <input type="text" name="search" placeholder="Cari judul, nomor, atau deskripsi..."
                                        value="{{ request('search') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>

                                {{-- Filter Kategori --}}
                                <div>
                                    <select name="category_id"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">-- Semua Kategori --</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tombol Filter --}}
                                <div>
                                    <button type="submit"
                                        class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        Cari / Filter
                                    </button>
                                </div>

                                {{-- Filter Rentang Tanggal --}}
                                <div class="md:col-span-2">
                                    <label for="date_from" class="text-sm text-gray-600">Dari Tanggal:</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="date_to" class="text-sm text-gray-600">Sampai Tanggal:</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                                </div>
                            </div>
                        </form>
                    </div>

                    <hr class="my-6 border-gray-200">
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
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tgl. Dokumen</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                {{-- KODE BENAR DI SINI: Gunakan $documents as $document --}}
                                @forelse ($documents as $document)
                                <tr class="border-b">
                                    <td class="text-left py-3 px-4">{{ $document->title }}</td>
                                    <td class="text-left py-3 px-4">{{ $document->category->name ?? '[Dihapus]' }}</td>
                                    <td class="text-left py-3 px-4">{{ $document->uploader->name ?? '[Dihapus]' }}</td>
                                    <td class="text-left py-3 px-4">
                                        {{ \Carbon\Carbon::parse($document->document_date)->format('d M Y') }}</td>
                                    <td class="text-left py-3 px-4">
                                        @if($document->status == 'Diarsip')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $document->status }}
                                        </span>
                                        @elseif($document->status == 'Menunggu Review')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $document->status }}
                                        </span>
                                        @else {{-- Ditolak --}}
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $document->status }}
                                        </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('documents.show', $document->id) }}"
                                                class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">Detail</a>

                                            @can('update', $document)
                                            <a href="{{ route('documents.edit', $document->id) }}"
                                                class="text-sm bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 rounded">Edit</a>
                                            @endcan

                                            @can('delete', $document)
                                            {{-- Pastikan Form Hapus Anda Persis Seperti Ini --}}
                                            <form action="{{ route('documents.destroy', $document->id) }}" method="POST"
                                                onsubmit="return confirm('Anda yakin ingin menghapus dokumen ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded">Hapus</button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 px-4">
                                        Belum ada data dokumen.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Link untuk Paginasi --}}
                    <div class="mt-4">
                        {{-- UBAH BARIS INI --}}
                        {{ $documents->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>