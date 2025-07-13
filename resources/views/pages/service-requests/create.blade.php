<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Permohonan Surat Bebas Temuan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4 text-gray-600">Silakan unggah semua dokumen persyaratan yang tercantum di bawah ini.
                    </p>

                    @if ($errors->any())
                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <p class="font-bold">Terjadi Kesalahan</p>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('service-requests.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            @forelse($requiredDocuments as $doc)
                            <div class="border-t border-gray-200 pt-6">
                                <label for="doc_{{ $doc->id }}" class="block font-medium text-sm text-gray-700">
                                    <strong>{{ $doc->name }}</strong>
                                </label>
                                @if($doc->description)
                                <p class="mt-1 text-sm text-gray-500">{{ $doc->description }}</p>
                                @endif
                                <input type="file"
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none mt-2"
                                    id="doc_{{ $doc->id }}" name="doc_{{ $doc->id }}" required>
                            </div>
                            @empty
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 text-center"
                                role="alert">
                                <p>Admin belum mengatur dokumen persyaratan. Silakan hubungi administrator.</p>
                            </div>
                            @endforelse
                        </div>

                        @if($requiredDocuments->isNotEmpty())
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Ajukan Permohonan
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>