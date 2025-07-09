<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-gray-800 leading-tight">Tambah Entitas Baru</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-visible shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                <form action="{{ route('entities.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Entitas (OPD/Kecamatan/Desa)</label>
                        {{-- Tambahkan class di input ini --}}
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    </div>

                    <div class="mb-4">
                        <label for="agency_code" class="block text-sm font-medium text-gray-700">Kode Instansi (Opsional)</label>
                        <input type="text" name="agency_code" id="agency_code" value="{{ old('agency_code', $entity->agency_code ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                        {{-- Tambahkan class di select ini --}}
                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="OPD">OPD</option>
                            <option value="Kecamatan">Kecamatan</option>
                            <option value="Desa">Desa</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="parent_id" class="block text-sm font-medium text-gray-700">Induk Entitas (Opsional)</label>
                        {{-- Tambahkan class di select ini --}}
                        <select name="parent_id" id="parent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">-- Tidak Ada Induk --</option>
                            @foreach ($parentEntities as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('entities.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">Simpan</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
    new TomSelect('#parent_id',{
        create: false,
        sortField: { field: "text", direction: "asc" }
    });
    </script>
    @endpush
</x-app-layout>