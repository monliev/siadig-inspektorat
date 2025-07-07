<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail & Review Dokumen OPD
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 font-semibold mb-4 inline-block">&larr; Kembali ke Daftar Unggahan</a>
                
                <h3 class="text-2xl font-bold text-gray-900">{{ $document->title }}</h3>
                <p class="text-sm text-gray-500">Dikirim oleh: <strong>{{ $document->uploader->entity->name ?? $document->uploader->name }}</strong></p>
                <p class="text-sm text-gray-500">Terkait Permintaan: <strong>{{ $document->documentRequest->title ?? '-' }}</strong></p>

                <hr class="my-4">

                <div class="flex items-center space-x-2">
                    <a href="{{ route('documents.download', $document->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Unduh File</a>
                    
                    @if ($document->status == 'Menunggu Review')
                        <form action="{{ route('documents.approve', $document->id) }}" method="POST" onsubmit="return confirm('Setujui dokumen ini?');">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="redirect_to" value="{{ route('document-requests.show', $document->document_request_id) }}">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 ...">Approve</button>
                        </form>
                        <form action="{{ route('documents.reject', $document->id) }}" method="POST" onsubmit="return confirm('Tolak dokumen ini?');">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="redirect_to" value="{{ url()->previous() }}">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 ...">Reject</button>
                        </form>
                    @else
                         <span class="px-3 py-2 inline-flex text-sm leading-5 font-semibold rounded-md {{ $document->status == 'Diarsip' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            Status: {{ $document->status }}
                        </span>
                    @endif
                </div>
            </div>

            @if (Illuminate\Support\Str::endsWith($document->stored_path, '.pdf'))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <iframe src="{{ asset('storage/'.$document->stored_path) }}" width="100%" height="700px"></iframe>
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
                                        <td class="py-3 px-4">{{ $activity->created_at->translatedFormat('d F Y, H:i') }}</td>
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