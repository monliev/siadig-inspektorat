<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Unggah Dokumen Internal Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Terjadi kesalahan!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Form untuk unggah dokumen --}}
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori
                                Dokumen</label>
                            <select name="category_id" id="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Dokumen</label>
                            <input type="text" name="title" id="title"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="document_number" class="block text-sm font-medium text-gray-700">Nomor Dokumen
                                (Opsional)</label>
                            <input type="text" name="document_number" id="document_number"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="mb-4">
                            <label for="document_date" class="block text-sm font-medium text-gray-700">Tanggal
                                Dokumen</label>
                            <input type="date" name="document_date" id="document_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                                (Opsional)</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                        </div>

                        {{-- Bagian Korespondensi --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Informasi Korespondensi</h3>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center">
                                    <input id="type_outgoing" name="correspondence_type" type="radio" value="outgoing"
                                        class="h-4 w-4 text-indigo-600 border-gray-300" checked>
                                    <label for="type_outgoing"
                                        class="ms-3 block text-sm font-medium text-gray-700">Surat Keluar / Dokumen
                                        Internal</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="type_incoming" name="correspondence_type" type="radio" value="incoming"
                                        class="h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="type_incoming"
                                        class="ms-3 block text-sm font-medium text-gray-700">Surat Masuk</label>
                                </div>
                            </div>

                            {{-- Form untuk Surat Keluar/Internal --}}
                            <div id="outgoing_fields" class="mt-4 space-y-4">
                                <div>
                                    <label for="to_entity_id" class="block text-sm font-medium text-gray-700">Tujuan
                                        Dokumen</label>
                                    <select name="to_entity_id" id="to_entity_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">-- Pilih Tujuan --</option>
                                        @foreach ($entities as $entity)
                                        <option value="{{ $entity->id }}">{{ $entity->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Form untuk Surat Masuk --}}
                            <div id="incoming_fields" class="mt-4 space-y-4" style="display: none;">
                                <div>
                                    <label for="from_entity_id" class="block text-sm font-medium text-gray-700">Pengirim
                                        Dokumen</label>
                                    <select name="from_entity_id" id="from_entity_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">-- Pilih Pengirim --</option>
                                        @foreach ($entities as $entity)
                                        <option value="{{ $entity->id }}">
                                            {{-- PERUBAHAN DI SINI --}}
                                            {{ $entity->name }} @if($entity->agency_code) ({{ $entity->agency_code }})
                                            @endif
                                        </option>
                                        @endforeach
                                        <option value="is_external">-- Pengirim dari Luar Pemkab --</option>
                                    </select>
                                </div>
                                <div id="external_sender_field" style="display: none;">
                                    <label for="external_sender_name"
                                        class="block text-sm font-medium text-gray-700">Nama Pengirim Eksternal</label>
                                    <input type="text" name="external_sender_name" id="external_sender_name"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-b border-gray-200 py-4 my-4">
                            <h3 class="text-base font-semibold text-gray-800 mb-2">Lokasi Fisik (Opsional)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="physical_location_building"
                                        class="block text-sm font-medium text-gray-700">Gedung/Ruangan</label>
                                    <input type="text" name="physical_location_building" id="physical_location_building"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="physical_location_cabinet"
                                        class="block text-sm font-medium text-gray-700">Lemari</label>
                                    <input type="text" name="physical_location_cabinet" id="physical_location_cabinet"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="physical_location_rack"
                                        class="block text-sm font-medium text-gray-700">Rak</label>
                                    <input type="text" name="physical_location_rack" id="physical_location_rack"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                <div>
                                    <label for="physical_location_box"
                                        class="block text-sm font-medium text-gray-700">Boks/Map</label>
                                    <input type="text" name="physical_location_box" id="physical_location_box"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">Pilih File</label>
                            <input type="file" name="file" id="file" class="mt-1 block w-full" required
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Dokumen
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const correspondenceTypeRadios = document.querySelectorAll('input[name="correspondence_type"]');
        const outgoingFields = document.getElementById('outgoing_fields');
        const incomingFields = document.getElementById('incoming_fields');
        const fromEntitySelect = document.getElementById('from_entity_id');
        const externalSenderField = document.getElementById('external_sender_field');

        function toggleFields() {
            if (document.getElementById('type_incoming').checked) {
                incomingFields.style.display = 'block';
                outgoingFields.style.display = 'none';
            } else {
                incomingFields.style.display = 'none';
                outgoingFields.style.display = 'block';
            }
        }

        function toggleExternalSender() {
            if (fromEntitySelect.value === 'is_external') {
                externalSenderField.style.display = 'block';
            } else {
                externalSenderField.style.display = 'none';
            }
        }

        correspondenceTypeRadios.forEach(radio => radio.addEventListener('change', toggleFields));
        fromEntitySelect.addEventListener('change', toggleExternalSender);

        // Initial state
        toggleFields();
        toggleExternalSender();
    });
    </script>
    @endpush

</x-app-layout>