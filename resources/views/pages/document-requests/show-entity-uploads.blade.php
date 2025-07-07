<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Review Unggahan dari: {{ $entity->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('document-requests.show', $documentRequest->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-4 inline-block">&larr; Kembali ke Progres Permintaan</a>

                    <p class="text-sm text-gray-600">Untuk permintaan: <span class="font-semibold">{{ $documentRequest->title }}</span></p>

                    <hr class="my-4">

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4">Judul Dokumen</th>
                                    <th class="text-left py-3 px-4">Pengunggah</th>
                                    <th class="text-left py-3 px-4">Waktu Unggah</th>
                                    <th class="text-center py-3 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($documents as $document)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $document->title }}</td>
                                    <td class="py-3 px-4">{{ $document->uploader->name }}</td>
                                    <td class="py-3 px-4">{{ $document->created_at->translatedFormat('d M Y, H:i') }}</td>
                                    <td class="text-center py-3 px-4">
                                        
                                        <div class="flex items-center justify-center space-x-2">
                                            {{-- Tombol Detail tetap ada untuk melihat file --}}
                                            <a href="{{ route('client-submissions.show', $document->id) }}" class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">Lihat</a>

                                            {{-- Tombol Aksi Review --}}
                                            @if ($document->status == 'Menunggu Review')
                                                <form action="{{ route('documents.approve', $document->id) }}" method="POST" onsubmit="return confirm('Setujui dokumen ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                                    <button type="submit" class="text-sm bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded">Approve</button>
                                                </form>
                                                <form action="{{ route('documents.reject', $document->id) }}" method="POST" onsubmit="return confirm('Tolak dokumen ini?');">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                                    <button type="submit" class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded">Reject</button>
                                                </form>
                                            @else
                                                {{-- Tampilkan status jika sudah direview --}}
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $document->status == 'Diarsip' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $document->status }}
                                                </span>
                                            @endif
                                        </div>

                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-4">Entitas ini belum mengunggah dokumen apapun.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>