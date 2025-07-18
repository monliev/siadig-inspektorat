<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ruang Kerja Disposisi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <x-session-alert />
            @can('view', $disposition)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="flex justify-between items-start flex-wrap gap-4">
                    {{-- Informasi Disposisi --}}
                    <div>
                        <a href="{{ route('dispositions.index') }}"
                            class="text-blue-600 hover:text-blue-800 font-semibold mb-4 inline-block">&larr; Kembali</a>
                        <h3 class="text-lg font-medium text-gray-900">
                            
                            Dari: 
                            <strong>
                            {{-- Kode Baru yang Sudah Diperbaiki --}}
                            @if($disposition->onBehalfOfUser)
                                {{-- Jika disposisi dibuat atas nama pimpinan, tampilkan nama & jabatannya --}}
                                <strong>{{ $disposition->onBehalfOfUser->name }} - {{ $disposition->onBehalfOfUser->jabatan }}</strong>
                            @else
                                {{-- Jika tidak, tampilkan nama & jabatan pengirim asli --}}
                                <strong>{{ $disposition->fromUser->name }}</strong>
                                <span class="block text-xs text-gray-500">{{ $disposition->fromUser->jabatan }}</span>
                            @endif
                            </strong>
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">Terkait Dokumen:
                            <a href="{{ route('documents.show', $disposition->document_id) }}"
                                class="text-blue-600 hover:underline" target="_blank">
                                {{ $disposition->document->title }}
                            </a>
                        </p>
                    </div>

                    {{-- Tombol Aksi Selesaikan dengan Modal --}}
                    <div>
                        {{-- PERBAIKAN: Gunakan @can untuk otorisasi --}}
                        @can('markAsCompleted', $disposition)
                        @if ($disposition->status !== 'Selesai')
                        <div x-data="{ open: false }">
                            <button @click="open = true"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Selesaikan Disposisi
                            </button>

                            {{-- Modal Konfirmasi --}}
                            <div x-show="open" x-transition.opacity style="display: none;"
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
                                <div @click.away="open = false"
                                    class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg mx-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Konfirmasi Penyelesaian</h3>
                                    <p class="text-sm text-gray-600 mb-4">Anda yakin ingin menutup disposisi ini? Aksi
                                        ini tidak dapat dibatalkan.</p>
                                    <form action="{{ route('dispositions.complete', $disposition->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label for="closing_note"
                                                class="block text-sm font-medium text-gray-700">Catatan Penutupan
                                                (Opsional)</label>
                                            <textarea name="closing_note" id="closing_note" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                        </div>
                                        <div class="flex justify-end space-x-2 mt-6">
                                            <button type="button" @click="open = false"
                                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Ya,
                                                Selesaikan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endcan
                    </div>
                </div>
                <div class="mt-4 p-4 bg-gray-50 rounded-md border">
                    <p class="text-gray-800">{{ $disposition->instructions }}</p>
                </div>
            </div>
            @endcan
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Preview Dokumen Lampiran</h3>

                    @php
                    $document = $disposition->document;
                    $extension = pathinfo($document->stored_path, PATHINFO_EXTENSION);

                    // Buat URL sementara yang hanya berlaku selama 5 menit
                    $secureFileUrl = Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'documents.stream',
                    now()->addMinutes(5),
                    ['document' => $document->id]
                    );
                    @endphp

                    @if(in_array(strtolower($extension), ['pdf']))
                    {{-- Tampilkan PDF menggunakan tag <embed> atau <iframe> --}}
                    <div class="border rounded-md overflow-hidden">
                        <embed src="{{ $secureFileUrl }}" type="application/pdf" width="100%" height="700px" />
                    </div>

                    @elseif(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                    {{-- Tampilkan gambar menggunakan tag <img> --}}
                    <div class="border rounded-md p-4">
                        <img src="{{ $secureFileUrl }}" alt="Preview Dokumen" class="max-w-full h-auto mx-auto">
                    </div>

                    @else
                    {{-- Jika bukan PDF atau gambar, tampilkan pesan untuk download --}}
                    <div class="text-center bg-gray-100 p-8 rounded-md">
                        <p class="font-semibold">Preview tidak tersedia untuk tipe file ini (.{{ $extension }}).
                        </p>
                        <p class="text-sm text-gray-600 mt-1">Silakan unduh dokumen untuk melihatnya.</p>
                        <a href="{{ route('documents.download', $document->id) }}"
                            class="mt-4 inline-block bg-indigo-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-indigo-700">
                            Unduh Dokumen
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Tanggapan & Tindak Lanjut</h3>
                <div class="space-y-6">
                    @forelse ($disposition->responses as $response)
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold text-gray-800">{{ $response->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $response->created_at->translatedFormat('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="mt-2 p-3 bg-gray-50 rounded-md">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $response->notes }}</p>
                        </div>
                        {{-- Daftar Lampiran --}}
                        @if ($response->attachments->isNotEmpty())
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-600">Lampiran:</p>
                            <ul class="mt-1 list-disc list-inside space-y-1">
                                @foreach ($response->attachments as $attachment)
                                <li>
                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank"
                                        class="text-sm text-blue-600 hover:underline">
                                        {{ $attachment->original_filename }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-gray-500">Belum ada tanggapan.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if ($disposition->status !== 'Selesai')
            {{-- Jika disposisi BELUM selesai, tampilkan form tanggapan untuk penerima --}}
            @can('createResponse', $disposition)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Beri Tanggapan / Laporan Progres</h3>
                <form action="{{ route('dispositions.responses.store', $disposition->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <form action="{{ route('dispositions.responses.store', $disposition->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan / Laporan
                                Anda</label>
                            <textarea name="notes" id="notes" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="attachments" class="block text-sm font-medium text-gray-700">Lampirkan File
                                (Opsional)</label>
                            <input type="file" name="attachments[]" id="attachments"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" multiple>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Kirim
                                Tanggapan</button>
                        </div>
                    </form>
                </form>
            </div>
            @endcan
            @else
            {{-- ============================================= --}}
            {{--     JIKA SUDAH SELESAI, TAMPILKAN BLOK INI     --}}
            {{-- ============================================= --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Penutup Disposisi</h3>
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-sm text-gray-600">
                        <span>Status:</span>
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Selesai
                        </span>
                    </div>
                    <div class="mt-2 p-4 bg-green-50 border border-green-200 rounded-md">
                        <p class="text-sm font-medium text-green-800">Catatan Penutupan:</p>
                        <p class="text-gray-700 whitespace-pre-wrap">
                            {{ $disposition->closing_note ?? 'Tidak ada catatan penutupan.' }}</p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>