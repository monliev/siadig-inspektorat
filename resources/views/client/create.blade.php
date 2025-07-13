<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Unggah Dokumen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-blue-50 border-l-4 border-blue-400">
                <h3 class="text-lg font-medium text-blue-900">Anda merespons permintaan:</h3>
                <h4 class="text-2xl font-bold text-blue-900 mt-1">{{ $documentRequest->title }}</h4>
                @if($documentRequest->due_date)
                <p class="mt-1 text-sm text-red-600">
                    Batas Waktu: {{ \Carbon\Carbon::parse($documentRequest->due_date)->translatedFormat('d F Y') }}
                </p>
                @endif
                <p class="mt-2 text-sm text-gray-600">{{ $documentRequest->description }}</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Formulir Unggah</h3>
                    <form action="{{ route('client.requests.documents.store', $documentRequest->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Terjadi kesalahan!</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        {{-- ... semua input form Anda tetap di sini ... --}}
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul/Perihal
                                Dokumen</label>
                            <input type="text" name="title" id="title"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori
                                Dokumen</label>
                            <select name="category_id" id="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="document_date" class="block text-sm font-medium text-gray-700">Tanggal
                                Dokumen</label>
                            <input type="date" name="document_date" id="document_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                                (Opsional)</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">Pilih File (PDF, Word,
                                Excel, maks: 10MB)</label>
                            <input type="file" name="file" id="file" class="mt-1 block w-full" required>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('client.dashboard') }}"
                                class="text-gray-600 hover:text-gray-800 mr-4">Kembali</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Kirim Dokumen
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bagian Riwayat Unggahan --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Unggahan untuk Permintaan Ini</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama File</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Waktu Unggah</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($submittedDocuments as $document)
                                <tr class="border-b">
                                    <td class="py-3 px-4">{{ $document->original_filename }}</td>
                                    <td class="py-3 px-4">{{ $document->created_at->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $document->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-gray-500">Belum ada dokumen yang
                                        diunggah untuk permintaan ini.</td>
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