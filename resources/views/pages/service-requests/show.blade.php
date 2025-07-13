<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Permohonan #{{ $serviceRequest->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-2 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pemohon</h3>
                        <p><strong>Nama:</strong> {{ $serviceRequest->applicant->name }}</p>
                        <p><strong>Email:</strong> {{ $serviceRequest->applicant->email }}</p>
                        <p><strong>Tanggal Pengajuan:</strong> {{ $serviceRequest->created_at->format('d F Y, H:i') }}</p>
                        <p><strong>Status Saat Ini:</strong> <span class="font-semibold text-blue-600">{{ $serviceRequest->status }}</span></p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Dokumen Terunggah</h3>
                        <ul class="list-disc list-inside space-y-2">
                            @foreach($serviceRequest->uploadedDocuments as $document)
                                <li>
                                    <strong>{{ $document->requirement->name }}:</strong>
                                    <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="text-indigo-600 hover:underline ml-2">Lihat/Unduh Dokumen</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tindakan Verifikasi</h3>

                <form action="{{ route('service-requests.addRevision', $serviceRequest->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="notes" class="block font-medium text-sm text-gray-700">Catatan Revisi</label>
                            <textarea id="notes" name="notes" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Tuliskan dokumen apa yang kurang atau perlu diperbaiki..." required>{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Kirim Catatan & Minta Revisi
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="my-6">

                <form action="{{ route('service-requests.approve', $serviceRequest->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="final_document" class="block font-medium text-sm text-gray-700">Unggah Surat Bebas Temuan Final (PDF)</label>
                        <input type="file" name="final_document" id="final_document" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none mt-1" required>
                        @error('final_document')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Setujui & Kirim Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Revisi</h3>
                        @forelse($serviceRequest->revisions as $revision)
                            <div class="border-t pt-3 mt-3">
                                <p class="text-sm"><strong>{{ $revision->auditor->name }}</strong> pada {{ $revision->created_at->format('d M Y, H:i') }}</p>
                                <p class="mt-1">{{ $revision->notes }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Belum ada riwayat revisi.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>