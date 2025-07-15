<x-guest-layout>
    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
        {{-- TAMBAHKAN BLOK UNTUK MENAMPILKAN PESAN --}}
        @if(session('error_message'))
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">Gagal!</p>
            <p>{{ session('error_message') }}</p>
        </div>
        @endif
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Respons Disposisi</h2>
                {{-- Informasi Disposisi --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                    <p class="text-sm text-gray-500">Dari: <strong>{{ $disposition->fromUser->name }}</strong></p>
                    <p class="text-sm text-gray-500">Terkait Dokumen:
                        <strong>{{ $disposition->document->title }}</strong>
                    </p>
                    <hr class="my-2">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $disposition->instructions }}</p>
                </div>

                {{-- Form untuk Memberi Tanggapan --}}
                <form action="{{ route('dispositions.respond.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- KIRIM TOKEN UNTUK VALIDASI --}}
                    <input type="hidden" name="response_token" value="{{ $disposition->response_token }}">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Tanggapan / Laporan
                            Progres</label>
                        <textarea name="notes" id="notes" rows="5"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="attachments" class="block text-sm font-medium text-gray-700">Lampiran (Jika
                            Ada)</label>
                        <input type="file" name="attachments[]" id="attachments"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            multiple>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            Kirim Tanggapan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>