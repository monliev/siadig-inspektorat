<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kategori Dokumen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('document-categories.update', $documentCategory->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $documentCategory->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $documentCategory->description) }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Tipe Kategori</label>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center">
                                    <input id="scope_internal" name="scope" type="radio" value="internal" 
                                           {{ old('scope', $documentCategory->scope) == 'internal' ? 'checked' : '' }} 
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="scope_internal" class="ms-3 block text-sm font-medium text-gray-700">Internal (Hanya untuk Inspektorat)</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="scope_external" name="scope" type="radio" value="external" 
                                           {{ old('scope', $documentCategory->scope) == 'external' ? 'checked' : '' }} 
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="scope_external" class="ms-3 block text-sm font-medium text-gray-700">Eksternal (Untuk OPD & Desa)</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('document-categories.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Batal</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>