<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Permintaan Dokumen
            </h2>
            <a href="{{ route('document-requests.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                + Buat Permintaan Baru
            </a>
        </div>
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

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Judul Permintaan
                                    </th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Dibuat Oleh</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Batas Waktu</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Jumlah Tujuan</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($requests as $request)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $request->title }}</td>
                                    <td class="py-3 px-4">{{ $request->creator->name ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        {{ $request->due_date ? \Carbon\Carbon::parse($request->due_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="text-center py-3 px-4">{{ $request->entities_count }}</td>
                                    <td class="text-center py-3 px-4">
                                        <a href="{{ route('document-requests.show', $request->id) }}"
                                            class="text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded">Lihat
                                            Progres</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-10 text-gray-500">
                                        Belum ada permintaan dokumen yang dibuat.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>