<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Peran: ') }} {{ $role->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Peran</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required 
                                   {{-- Jangan biarkan Super Admin diubah namanya --}}
                                   {{ $role->name === 'Super Admin' ? 'readonly' : '' }}>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $role->description) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('roles.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Batal</a>
                            
                            {{-- Jangan tampilkan tombol simpan untuk Super Admin --}}
                            @if($role->name !== 'Super Admin')
                            <x-primary-button>
                                Simpan Perubahan
                            </x-primary-button>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>