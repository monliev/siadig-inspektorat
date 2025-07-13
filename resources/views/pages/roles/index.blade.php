<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Roles') }}
            </h2>
            {{-- Tombol Tambah dengan Gaya Baru --}}
            <a href="{{ route('roles.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                + Tambah Role Baru
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
                                    <th class="w-4/12 text-left py-3 px-4 uppercase font-semibold text-sm">Nama Role
                                    </th>
                                    <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Deskripsi</th>
                                    <th class="w-3/12 text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @forelse ($roles as $role)
                                <tr class="border-b">
                                    <td class="py-3 px-4">{{ $role->name }}</td>
                                    <td class="py-3 px-4">{{ $role->description }}</td>
                                    <td class="text-center py-3 px-4">
                                        {{-- Tombol Aksi dengan Gaya Baru --}}
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('roles.edit', $role->id) }}"
                                                class="text-sm bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-2 rounded">Edit</a>
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                onsubmit="return confirm('Anda yakin?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">Belum ada data role.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $roles->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>