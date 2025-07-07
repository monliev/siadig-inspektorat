<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Portal OPD') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Selamat Datang, {{ Illuminate\Support\Facades\Auth::user()->entity->name ?? Illuminate\Support\Facades\Auth::user()->name }}!</h3>
                    <p class="mb-6 text-gray-600">Berikut adalah daftar permintaan dokumen yang perlu Anda penuhi.</p>

                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Judul Permintaan</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Batas Waktu</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Status Anda</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($requests as $request)
                                    @php
                                        // Cek apakah user ini sudah mengunggah dokumen untuk permintaan ini
                                        $hasSubmitted = $request->documents->contains('uploaded_by', Illuminate\Support\Facades\Auth::id());
                                    @endphp
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $request->title }}</td>
                                        <td class="py-3 px-4">{{ $request->due_date ? \Carbon\Carbon::parse($request->due_date)->format('d M Y') : '-' }}</td>
                                        <td class="text-center py-3 px-4">
                                            @if ($hasSubmitted)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Sudah Dikirim
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Perlu Tindakan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center py-3 px-4">
                                            <a href="{{ route('client.requests.documents.create', $request->id) }}" class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">
                                                    {{ $hasSubmitted ? 'Lihat/Tambah File' : 'Unggah Dokumen' }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-10 text-gray-500">
                                            Saat ini belum ada permintaan dokumen untuk Anda.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $requests->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>