<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dokumen Kiriman Klien') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Form Pencarian Disederhanakan --}}
                    <form action="{{ route('documents.client_submissions') }}" method="GET" class="mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <input type="text" name="search" placeholder="Cari judul atau nama entitas..."
                                    value="{{ request('search') }}" class="w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <button type="submit"
                                    class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-white uppercase tracking-widest hover:bg-gray-500">Cari</button>
                            </div>
                        </div>
                    </form>

                    <hr class="my-6 border-gray-200">
                    @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                    @endif

                    <div class="mb-4 flex space-x-2">
                        <a href="{{ route('documents.client_submissions') }}"
                            class="px-3 py-1 text-sm font-semibold rounded-md {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800' }}">Semua</a>
                        <a href="{{ route('documents.client_submissions', ['status' => 'Menunggu Review']) }}"
                            class="px-3 py-1 text-sm font-semibold rounded-md {{ request('status') == 'Menunggu Review' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-800' }}">Menunggu
                            Review</a>
                        <a href="{{ route('documents.client_submissions', ['status' => 'Diarsip']) }}"
                            class="px-3 py-1 text-sm font-semibold rounded-md {{ request('status') == 'Diarsip' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-800' }}">Diterima</a>
                        <a href="{{ route('documents.client_submissions', ['status' => 'Ditolak']) }}"
                            class="px-3 py-1 text-sm font-semibold rounded-md {{ request('status') == 'Ditolak' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-800' }}">Ditolak</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Judul Dokumen</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Dikirim oleh
                                        (Entitas)</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Terkait Permintaan
                                    </th> {{-- KOLOM BARU --}}
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($documents as $document)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $document->title }}</td>
                                    <td class="py-3 px-4">{{ $document->uploader->entity->name ?? 'N/A' }}</td>
                                    <td class="py-3 px-4">
                                        {{-- LINK BARU KE PROGRES PERMINTAAN --}}
                                        @if ($document->documentRequest)
                                        <a href="{{ route('document-requests.show', $document->documentRequest->id) }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                            {{ $document->documentRequest->title }}
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        @php
                                        $statusClass = '';
                                        if ($document->status == 'Diarsip') $statusClass = 'bg-green-100
                                        text-green-800';
                                        elseif ($document->status == 'Menunggu Review') $statusClass = 'bg-yellow-100
                                        text-yellow-800';
                                        else $statusClass = 'bg-red-100 text-red-800';
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                            {{ $document->status }}
                                        </span>
                                    </td>
                                    <td class="text-center py-3 px-4">
                                        <a href="{{ route('client-submissions.show', $document->id) }}"
                                            class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">
                                            Detail & Review
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-gray-500">
                                        Tidak ada dokumen kiriman dari klien.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Link untuk Paginasi --}}
                    <div class="mt-4">
                        {{-- UBAH BARIS INI --}}
                        {{ $documents->appends(request()->query())->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>