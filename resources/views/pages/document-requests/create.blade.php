<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Permintaan Dokumen Baru</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('document-requests.store') }}" method="POST">
                        @csrf
                        {{-- Judul, Deskripsi, Batas Waktu --}}
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Judul Permintaan</label>
                            <input type="text" name="title" id="title"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="due_date" class="block text-sm font-medium text-gray-700">Batas Waktu
                                (Opsional)</label>
                            <input type="date" name="due_date" id="due_date"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        {{-- Pilihan Cepat --}}
                        <div class="p-4 border rounded-md bg-gray-50 mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Cepat</label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button"
                                    class="quick-select-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-1 px-3 rounded-full text-xs"
                                    data-ids="{{ $opdInduk->pluck('id')->toJson() }}">Semua OPD Induk</button>
                                <button type="button"
                                    class="quick-select-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-1 px-3 rounded-full text-xs"
                                    data-ids="{{ $semuaPuskesmas->pluck('id')->toJson() }}">Semua Puskesmas</button>
                                <button type="button"
                                    class="quick-select-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-1 px-3 rounded-full text-xs"
                                    data-ids="{{ $semuaKecamatan->pluck('id')->toJson() }}">Semua Kecamatan</button>
                                <button type="button"
                                    class="quick-select-btn bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-1 px-3 rounded-full text-xs"
                                    data-ids="{{ $semuaDesa->pluck('id')->toJson() }}">Semua Desa</button>
                                <button type="button" id="clear-selection-btn"
                                    class="bg-red-100 hover:bg-red-200 text-red-800 font-semibold py-1 px-3 rounded-full text-xs">Bersihkan
                                    Pilihan</button>
                            </div>
                            <div class="mt-3">
                                <label for="desa_per_kecamatan" class="block text-xs font-medium text-gray-600">Pilih
                                    Semua Desa di Kecamatan:</label>
                                <select id="desa_per_kecamatan"
                                    class="mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($desaPerKecamatan as $kecamatan => $desaIds)
                                    <option value="{{ $desaIds->toJson() }}">{{ $kecamatan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Dropdown Utama --}}
                        <div class="mb-4">
                            <label for="entity_ids" class="block text-sm font-medium text-gray-700">Tujukan ke Entitas
                                (bisa pilih lebih dari satu)</label>
                            <select name="entity_ids[]" id="entity_ids" class="mt-1 block w-full" multiple required>
                                @foreach ($entities as $entity)
                                <option value="{{ $entity->id }}">{{ $entity->name }} @if($entity->parent)
                                    ({{ $entity->parent->name }}) @endif</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end mt-4">
                            <a href="{{ route('document-requests.index') }}"
                                class="text-gray-600 hover:text-gray-800 mr-4">Batal</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Buat
                                Permintaan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
    .quick-select-btn {
        padding: 4px 12px;
        background-color: #e5e7eb;
        border: 1px solid #d1d5db;
        border-radius: 9999px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .quick-select-btn:hover {
        background-color: #d1d5db;
    }

    .quick-select-btn-clear {
        padding: 4px 12px;
        background-color: #fef2f2;
        border: 1px solid #fca5a5;
        color: #b91c1c;
        border-radius: 9999px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .quick-select-btn-clear:hover {
        background-color: #fee2e2;
    }
    </style>
    @endpush

    @push('scripts')
    <script>
    var tomSelect = new TomSelect('#entity_ids', {
        plugins: ['remove_button'],
        create: false,
    });

    document.querySelectorAll('.quick-select-btn').forEach(button => {
        button.addEventListener('click', function() {
            const ids = JSON.parse(this.dataset.ids);
            tomSelect.addItems(ids);
        });
    });

    document.getElementById('desa_per_kecamatan').addEventListener('change', function() {
        if (this.value) {
            const ids = JSON.parse(this.value);
            tomSelect.addItems(ids);
            this.value = ""; // Reset dropdown setelah dipilih
        }
    });

    document.getElementById('clear-selection-btn').addEventListener('click', function() {
        tomSelect.clear();
    });
    </script>
    @endpush
</x-app-layout>