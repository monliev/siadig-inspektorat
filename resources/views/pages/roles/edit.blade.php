<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role: ') }} {{ $role->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Gunakan method PUT untuk update --}}

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Role</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $role->description) }}</textarea>
                        </div>

                        <hr>
                        <h4>Hak Akses (Permissions)</h4>

                        @if($role->name === 'Super Admin')
                        <div class="alert alert-info mt-3">
                            Super Admin memiliki akses penuh ke semua fitur secara default dan tidak dapat diubah.
                        </div>
                        @else
                        <div class="form-group mt-3">
                            @foreach($permissions as $permission)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    id="perm-{{ $permission->id }}" value="{{ $permission->name }}"
                                    {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                <label class="form-check-label"
                                    for="perm-{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('roles.index') }}"
                                class="text-gray-600 hover:text-gray-800 mr-4">Batal</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>