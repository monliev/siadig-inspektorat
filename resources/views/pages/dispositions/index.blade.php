<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Disposisi Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                    @endif

                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="text-left py-3 px-4">Dokumen</th>
                                <th class="text-left py-3 px-4">Instruksi</th>
                                <th class="text-left py-3 px-4">Dari</th>
                                <th class="text-left py-3 px-4">Tanggal</th>
                                <th class="text-center py-3 px-4">Status</th>
                                <th class="text-center py-3 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse ($dispositions as $disposition)
                            <tr
                                class="border-b {{ $disposition->status == 'Selesai' ? 'bg-gray-50 text-gray-500' : 'hover:bg-gray-50' }}">
                                <td class="py-3 px-4">
                                    <a href="{{ route('dispositions.show', ['disposition' => $disposition->id, 'return_to' => 'inbox']) }}"
                                        class="text-blue-600 hover:text-blue-800">
                                        {{ $disposition->document->title }}
                                    </a>
                                </td>
                                <td class="py-3 px-4">
                                    {{ \Illuminate\Support\Str::limit($disposition->instructions, 50) }}</td>
                                <td class="py-3 px-4">{{ $disposition->fromUser->name }}</td>
                                <td class="py-3 px-4">{{ $disposition->created_at->translatedFormat('d M Y') }}</td>
                                <td class="text-center py-3 px-4">
                                    @php
                                    $statusClass = 'bg-gray-100 text-gray-800'; // Terkirim
                                    if ($disposition->status == 'Selesai') {
                                    $statusClass = 'bg-green-100 text-green-800';
                                    } elseif ($disposition->status == 'Dibaca') {
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                    } elseif ($disposition->status == 'Dibalas') {
                                    $statusClass = 'bg-purple-100 text-purple-800';
                                    }
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $disposition->status }}
                                    </span>
                                </td>
                                <td class="text-center py-3 px-4">
                                    @if ($disposition->status !== 'Selesai')
                                    <div x-data="{ open: false }">
                                        <button @click="open = true"
                                            class="text-sm bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded">
                                            Selesaikan
                                        </button>
                                        <div x-show="open" x-transition.opacity style="display: none;"
                                            class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 p-4 text-left">
                                            <div @click.away="open = false"
                                                class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg mx-4">
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">Konfirmasi
                                                    Penyelesaian</h3>
                                                <p class="text-sm text-gray-600 mb-4">Anda yakin ingin menutup disposisi
                                                    untuk dokumen "{{ $disposition->document->title }}"?</p>
                                                <form action="{{ route('dispositions.complete', $disposition->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div>
                                                        <label for="closing_note_{{ $disposition->id }}"
                                                            class="block text-sm font-medium text-gray-700">Catatan
                                                            Penutupan (Opsional)</label>
                                                        <textarea name="closing_note"
                                                            id="closing_note_{{ $disposition->id }}" rows="3"
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                                                    </div>
                                                    <div class="flex justify-end space-x-2 mt-6">
                                                        <button type="button" @click="open = false"
                                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Batal</button>
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Ya,
                                                            Selesaikan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-sm font-semibold text-gray-400">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-10">Tidak ada disposisi masuk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $dispositions->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>