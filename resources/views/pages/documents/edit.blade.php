<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Dokumen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('documents.update', $document->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Layout 1 Kolom --}}
                        <div class="space-y-4">

                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Judul Dokumen</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $document->title) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            <div>
                                <label for="document_number" class="block text-sm font-medium text-gray-700">Nomor
                                    Dokumen (Opsional)</label>
                                <input type="text" name="document_number" id="document_number"
                                    value="{{ old('document_number', $document->document_number) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <div>
                                <label for="document_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Dokumen</label>
                                <input type="date" name="document_date" id="document_date"
                                    value="{{ old('document_date', \Carbon\Carbon::parse($document->document_date)->format('Y-m-d')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            </div>

                            <div>
                                <label for="category_id"
                                    class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="category_id" id="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $document->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                                    (Opsional)</label>
                                {{-- Nilai harus langsung menempel pada tag textarea tanpa spasi atau enter --}}
                                <textarea name="description" id="description" rows="5"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description', $document->description) }}</textarea>
                            </div>

                            <div class="p-4 border rounded-md bg-gray-50">
                                <h4 class="font-semibold text-gray-600">Lokasi Fisik Arsip (Opsional)</h4>
                                <div class="grid grid-cols-2 gap-4 mt-2">
                                    <div>
                                        <label for="physical_location_building"
                                            class="block text-xs font-medium text-gray-600">Gedung/Ruangan</label>
                                        <input type="text" name="physical_location_building"
                                            value="{{ old('physical_location_building', $document->physical_location_building) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                    </div>
                                    <div>
                                        <label for="physical_location_cabinet"
                                            class="block text-xs font-medium text-gray-600">Lemari</label>
                                        <input type="text" name="physical_location_cabinet"
                                            value="{{ old('physical_location_cabinet', $document->physical_location_cabinet) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                    </div>
                                    <div>
                                        <label for="physical_location_rack"
                                            class="block text-xs font-medium text-gray-600">Rak</label>
                                        <input type="text" name="physical_location_rack"
                                            value="{{ old('physical_location_rack', $document->physical_location_rack) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                    </div>
                                    <div>
                                        <label for="physical_location_box"
                                            class="block text-xs font-medium text-gray-600">Boks/Map</label>
                                        <input type="text" name="physical_location_box"
                                            value="{{ old('physical_location_box', $document->physical_location_box) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('documents.index') }}"
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