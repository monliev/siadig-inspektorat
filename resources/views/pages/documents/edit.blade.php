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

                    <form action="{{ route('documents.update', $document->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Dokumen</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $document->title) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div class="border-t border-b border-gray-200 py-4 my-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-2">Lokasi Fisik</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="physical_location_building"
                                        class="block text-sm font-medium text-gray-700">Gedung/Ruangan</label>
                                    <input type="text" name="physical_location_building" id="physical_location_building"
                                        value="{{ old('physical_location_building', $document->physical_location_building) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="physical_location_cabinet"
                                        class="block text-sm font-medium text-gray-700">Lemari</label>
                                    <input type="text" name="physical_location_cabinet" id="physical_location_cabinet"
                                        value="{{ old('physical_location_cabinet', $document->physical_location_cabinet) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="physical_location_rack"
                                        class="block text-sm font-medium text-gray-700">Rak</label>
                                    <input type="text" name="physical_location_rack" id="physical_location_rack"
                                        value="{{ old('physical_location_rack', $document->physical_location_rack) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="physical_location_box"
                                        class="block text-sm font-medium text-gray-700">Boks/Map</label>
                                    <input type="text" name="physical_location_box" id="physical_location_box"
                                        value="{{ old('physical_location_box', $document->physical_location_box) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('documents.show', $document->id) }}"
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