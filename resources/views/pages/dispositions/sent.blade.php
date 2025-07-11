<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Disposisi Keluar (Terkirim)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="text-left py-3 px-4">Dokumen</th>
                                <th class="text-left py-3 px-4">Tujuan</th>
                                <th class="text-left py-3 px-4">Tanggal Kirim</th>
                                <th class="text-center py-3 px-4">Status</th>
                            </tr>
                        </thead>
                       <tbody class="text-gray-700">
                            @forelse ($dispositions as $disposition)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <a href="{{ route('dispositions.show', $disposition->id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $disposition->document->title }}
                                    </a>
                                </td>
                                <td class="py-3 px-4">{{ $disposition->toUser->name }}</td>
                                <td class="py-3 px-4">{{ $disposition->created_at->translatedFormat('d M Y') }}</td>
                                <td class="text-center py-3 px-4">
                                    {{-- ============================================= --}}
                                    {{--         PERBAIKAN ADA DI BLOK INI             --}}
                                    {{-- ============================================= --}}
                                    @php
                                        $statusClass = 'bg-gray-100 text-gray-800'; // Default untuk 'Terkirim'
                                        if ($disposition->status == 'Selesai' || $disposition->status == 'Ditindaklanjuti') {
                                            $statusClass = 'bg-green-100 text-green-800';
                                        } elseif ($disposition->status == 'Dibaca' || $disposition->status == 'Dibalas') {
                                            $statusClass = 'bg-blue-100 text-blue-800';
                                        }
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $disposition->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-10">Anda belum mengirim disposisi apapun.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $dispositions->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>