<x-guest-layout>
    <div class="max-w-5xl mx-auto p-6 space-y-6">

        {{-- Judul Halaman --}}
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-800">Halaman Respons Disposisi</h1>
            <p class="text-sm text-gray-500 mt-1">Isi tanggapan atas disposisi yang Anda terima</p>
        </div>

        {{-- Notifikasi Error --}}
        @if(session('error_message'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow" role="alert">
            <p class="font-bold">Gagal!</p>
            <p>{{ session('error_message') }}</p>
        </div>
        @endif

        {{-- Kartu: Keterangan Disposisi --}}
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Keterangan Disposisi</h2>
            <div class="space-y-2 text-sm text-gray-700">
                <p><span class="text-gray-500">Dari:</span> <strong>{{ $disposition->fromUser->name }}</strong></p>
                <p><span class="text-gray-500">Terkait Dokumen:</span> <strong>{{ $disposition->document->title }}</strong></p>
                <div class="bg-gray-100 text-gray-800 p-4 rounded-lg border border-gray-300 shadow-inner">
                    <p class="whitespace-pre-wrap font-medium">{{ $disposition->instructions }}</p>
                </div>
            </div>
        </div>

        {{-- Kartu: Preview Dokumen --}}
        @if($previewUrl)
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Preview Dokumen Lampiran</h2>
            <div class="border rounded-md overflow-hidden">
                <embed src="{{ $previewUrl }}" type="application/pdf" width="100%" height="600px" />
            </div>
        </div>
        @endif

        {{-- Kartu: Form Tanggapan --}}
        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Form Tanggapan / Progres</h2>
            <form action="{{ route('dispositions.respond.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="response_token" value="{{ $disposition->response_token }}">
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Tanggapan / Laporan Progres</label>
                    <textarea name="notes" id="notes" rows="5"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                </div>

                <div>
                    <label for="attachments" class="block text-sm font-medium text-gray-700">Lampiran (Opsional)</label>
                    <input type="file" name="attachments[]" id="attachments" multiple
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-md shadow">
                        Kirim Tanggapan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>