<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Dokumen Internal: {{ $document->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- KOTAK UTAMA DETAIL & AKSI --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6">
                        <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 font-semibold">&larr;
                            Kembali</a>
                    </div>

                    {{-- AREA TOMBOL AKSI --}}
                    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                        {{-- Tombol-tombol Aksi Kiri --}}
                        <div class="flex items-center flex-wrap gap-2">
                            <a href="{{ route('documents.download', $document->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">
                                Unduh File
                            </a>
                            @can('update', $document)
                            <a href="{{ route('documents.edit', $document->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400">
                                Edit
                            </a>
                            @endcan
                            @if ($document->status == 'Menunggu Review')
                            @can('reviewAny', \App\Models\Document::class)
                            <form action="{{ route('documents.approve', $document->id) }}" method="POST"
                                onsubmit="return confirm('Setujui dokumen ini?');" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">Approve</button>
                            </form>
                            <form action="{{ route('documents.reject', $document->id) }}" method="POST"
                                onsubmit="return confirm('Tolak dokumen ini?');" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">Reject</button>
                            </form>
                            @endcan
                            @endif
                        </div>

                        {{-- Tombol Disposisi di Kanan --}}
                        @can('can-disposition')
                        <div x-data="{ open: false }">
    <button @click="open = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
        Buat Disposisi
    </button>

    <div x-show="open" x-transition.opacity style="display: none;" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
        
        {{-- REVISI: class 'rounded-lg' dihapus dari div ini untuk membuatnya kotak --}}
        <div @click.away="open = false" class="bg-white shadow-xl p-6 w-full max-w-lg mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Formulir Disposisi</h3>
            
            <form action="{{ route('dispositions.store', $document->id) }}" method="POST">
                @csrf
                
                {{-- REVISI: Bagian dropdown 'roles' dihapus --}}

                <div class="mb-4">
                    <label for="users" class="block text-sm font-medium text-gray-700">Kirim ke Pegawai (Bisa Pilih Banyak)</label>
                    <select name="users[]" id="users" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" multiple required>
                        @foreach($internalUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->jabatan }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Tahan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu.</small>
                </div>

                <div class="mb-4">
                    <label for="instructions" class="block text-sm font-medium text-gray-700">Instruksi / Catatan</label>
                    <textarea name="instructions" id="instructions" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" @click="open = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500">Kirim Disposisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
                    @endcan
                    </div>


                    {{-- Detail Dokumen dalam bentuk tabel --}}
                    <div class="border-t border-gray-200 mt-6">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Judul</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $document->title }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nomor Dokumen</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $document->document_number ?? '-' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Tanggal Dokumen</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') }}
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $document->category->name ?? '[Dihapus]' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-wrap">{{ $document->description ?? '-' }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Diunggah Oleh</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $document->uploader->name ?? '[Dihapus]' }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Waktu Unggah</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $document->created_at->translatedFormat('d F Y, H:i') }} WIB</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Lokasi Fisik</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $document->physical_location_building ?? '' }} /
                                    {{ $document->physical_location_cabinet ?? '' }} /
                                    {{ $document->physical_location_rack ?? '' }} /
                                    {{ $document->physical_location_box ?? '' }}
                                </dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Nama File Asli</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ $document->original_filename }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- KOTAK PREVIEW PDF --}}
            @if (Illuminate\Support\Str::endsWith($document->stored_path, '.pdf'))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <iframe src="{{ asset('storage/' . $document->stored_path) }}" width="100%" height="700px"
                    class="border-0"></iframe>
            </div>
            @endif

            {{-- KOTAK RIWAYAT DOKUMEN --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Dokumen</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Pengguna</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($activities as $activity)
                                <tr class="border-b">
                                    <td class="py-3 px-4">{{ $activity->user->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4">{{ $activity->description }}</td>
                                    <td class="py-3 px-4">{{ $activity->created_at->translatedFormat('d F Y, H:i') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">Belum ada riwayat untuk dokumen ini.</td>
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