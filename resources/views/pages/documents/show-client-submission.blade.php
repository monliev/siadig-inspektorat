<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail & Review Dokumen Klien
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <a href="{{ url()->previous() }}"
                    class="text-blue-600 hover:text-blue-800 font-semibold mb-4 inline-block">&larr; Kembali ke Daftar
                    Unggahan</a>

                <h3 class="text-2xl font-bold text-gray-900">{{ $document->title }}</h3>
                <p class="text-sm text-gray-500">Dikirim oleh:
                    <strong>{{ $document->uploader->entity->name ?? $document->uploader->name }}</strong></p>
                <p class="text-sm text-gray-500">Terkait Permintaan:
                    <strong>{{ $document->documentRequest->title ?? '-' }}</strong></p>

                <hr class="my-4">

                {{-- Area Tombol Aksi --}}
                <div class="flex flex-wrap justify-between items-start gap-4">
                    {{-- Tombol-tombol Aksi Kiri --}}
                    <div class="flex items-center flex-wrap gap-2">
                        <a href="{{ route('documents.download', $document->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500">Unduh
                            File</a>
                        @if ($document->status == 'Menunggu Review')
                        @can('reviewAny', \App\Models\Document::class)
                        <form action="{{ route('documents.approve', $document->id) }}" method="POST"
                            onsubmit="return confirm('Setujui dokumen ini?');" class="inline-block">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="redirect_to"
                                value="{{ route('document-requests.show', $document->document_request_id) }}">
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
                        @else
                        <span
                            class="px-3 py-2 inline-flex text-sm leading-5 font-semibold rounded-md {{ $document->status == 'Diarsip' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            Status: {{ $document->status }}
                        </span>
                        @endif
                    </div>

                    {{-- Tombol Disposisi di Kanan --}}
                    @can('can-disposition')
                    <div x-data="{ open: false }">
                        <button @click="open = true"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">Disposisi</button>
                        <div x-show="open" x-transition.opacity style="display: none;"
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4">
                            <div @click.away="open = false"
                                class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg mx-4">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Formulir Disposisi</h3>
                                <form action="{{ route('dispositions.store', $document->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="to_user_id" class="block text-sm font-medium text-gray-700">Tujuan
                                            Disposisi</label>
                                        <select name="to_user_id" id="to_user_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                            <option value="">-- Pilih Pegawai --</option>
                                            @foreach ($internalUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->jabatan }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label for="instructions"
                                            class="block text-sm font-medium text-gray-700">Instruksi / Catatan</label>
                                        <textarea name="instructions" id="instructions" rows="4"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                            required></textarea>
                                    </div>
                                    <div class="flex justify-end space-x-2 mt-6">
                                        <button type="button" @click="open = false"
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500">Kirim
                                            Disposisi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>

            @if (Illuminate\Support\Str::endsWith($document->stored_path, '.pdf'))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <iframe src="{{ asset('storage/'.$document->stored_path) }}" width="100%" height="700px"
                    class="border-0"></iframe>
            </div>
            @endif

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
                                    <td class="py-3 px-4">{{ $activity->created_at->translatedFormat('d M Y, H:i') }}
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