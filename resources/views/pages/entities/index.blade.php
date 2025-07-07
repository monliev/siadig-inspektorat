<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Entitas (OPD & Desa)</h2>
            <a href="{{ route('entities.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                + Tambah Entitas
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('entities.index') }}" method="GET" class="mb-4">
                        <div class="flex space-x-2">
                            <input type="text" name="search" placeholder="Cari nama entitas atau induknya..." value="{{ $search ?? '' }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Cari</button>
                            <a href="{{ route('entities.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300">Reset</a>
                        </div>
                    </form>

                    <hr class="my-4">

                    {{-- Judul Hasil Pencarian (jika ada) --}}
                    @if ($search)
                    <div class="mb-4">
                        <p class="text-sm text-gray-700">
                            Menampilkan hasil pencarian untuk: <span class="font-semibold">{{ $search }}</span>
                        </p>
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama OPD (Entitas)</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Tipe</th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">OPD Induk</th>
                                    <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($entities as $entity)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $entity->name }}</td>
                                        <td class="py-3 px-4">{{ $entity->type }}</td>
                                        <td class="py-3 px-4">{{ $entity->parent->name ?? '-' }}</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('entities.edit', $entity->id) }}" class="text-sm bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 rounded">Edit</a>
                                                <form action="{{ route('entities.destroy', $entity->id) }}" method="POST" onsubmit="return confirm('Anda yakin?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-10 text-gray-500">
                                            @if ($search)
                                                Tidak ada entitas yang cocok dengan kata kunci <span class="font-semibold">"{{ $search }}"</span>.
                                            @else
                                                Belum ada data entitas.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $entities->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>