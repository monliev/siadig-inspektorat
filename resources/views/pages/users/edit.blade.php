<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- Nama --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="nip" class="block text-sm font-medium text-gray-700">NIP (Opsional)</label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip', $user->nip ?? '') }}" class="mt-1 block w-full ...">
                        </div>
                        <div class="mb-4">
                            <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan (Opsional)</label>
                            <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan', $user->jabatan ?? '') }}" class="mt-1 block w-full ...">
                        </div>
                        <div class="mb-4">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor WhatsApp (Opsional)</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}" class="mt-1 block w-full ..." placeholder="Contoh: 6281234567890">
                        </div>
                        {{-- Dropdown Role --}}
                        <div class="mb-4">
                            <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role_id" id="role_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dropdown Entitas --}}
                        <div class="mb-4">
                            <label for="entity_id" class="block text-sm font-medium text-gray-700">Entitas Terkait (untuk Klien Eksternal)</label>
                            <select name="entity_id" id="entity_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Tidak Terkait Entitas (Internal) --</option>
                                @foreach ($entities as $entity)
                                <option value="{{ $entity->id }}" {{ old('entity_id', $user->entity_id) == $entity->id ? 'selected' : '' }}>
                                    {{-- Jika punya induk, tampilkan nama induknya --}}
                                    {{ $entity->name }} @if($entity->parent) ({{ $entity->parent->name }}) @endif
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                        @push('scripts')
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            new TomSelect('#entity_id', {
                                create: false,
                                sortField: {
                                    field: "text",
                                    direction: "asc"
                                }
                            });
                        });
                        </script>
                        @endpush

                </div>
            </div>
        </div>
    </div>
</x-app-layout>