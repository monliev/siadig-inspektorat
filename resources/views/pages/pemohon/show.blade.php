<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Permohonan #{{ $serviceRequest->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Status Permohonan</h3>

                    {{-- Beri warna status --}}
                    @if($serviceRequest->status == 'SELESAI')
                    <span
                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        @elseif($serviceRequest->status == 'BUTUH REVISI')
                        <span
                            class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            @else
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                @endif
                                {{ $serviceRequest->status }}
                            </span>

                            @if($serviceRequest->status == 'SELESAI' && $serviceRequest->final_document_path)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-2">Permohonan Anda telah disetujui. Silakan unduh
                                    surat balasan Anda di bawah ini:</p>
                                <a href="{{ Storage::url($serviceRequest->final_document_path) }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                                    Unduh Surat Bebas Temuan
                                </a>
                            </div>
                            @endif
                </div>
            </div>

            @if($serviceRequest->revisions->isNotEmpty())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Catatan Revisi dari Auditor</h3>
                    @foreach($serviceRequest->revisions as $revision)
                    <div class="border-t pt-3 mt-3">
                        <p class="text-sm"><strong>{{ $revision->auditor->name }}</strong> pada
                            {{ $revision->created_at->format('d M Y, H:i') }}</p>
                        <blockquote class="mt-1 p-3 bg-gray-50 rounded-md border-l-4 border-gray-300">
                            {{ $revision->notes }}</blockquote>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif


            @if($serviceRequest->status == 'BUTUH REVISI')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Unggah Dokumen Perbaikan</h3>
                    <p class="text-sm text-gray-600 mb-4">Silakan unggah ulang dokumen yang perlu diperbaiki sesuai
                        catatan dari auditor.</p>

                    <form action="{{ route('service-requests.submitRevision', $serviceRequest->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-6">
                            {{-- Loop semua dokumen yang pernah diunggah untuk permohonan ini --}}
                            @foreach($serviceRequest->uploadedDocuments as $document)
                            <div class="border-t pt-4">
                                <label for="revisi_doc_{{ $document->required_document_id }}"
                                    class="block font-medium text-sm text-gray-700">
                                    Perbarui: <strong>{{ $document->requirement->name }}</strong>
                                </label>
                                <input type="file"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none mt-2"
                                    id="revisi_doc_{{ $document->required_document_id }}"
                                    name="revisi_docs[{{ $document->required_document_id }}]">
                                <p class="text-xs text-gray-500 mt-1">Unggah file baru untuk menggantikan file
                                    sebelumnya.</p>
                            </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Kirim Dokumen Perbaikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>