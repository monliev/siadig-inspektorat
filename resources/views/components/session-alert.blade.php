@if (session('success'))
    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-r-lg" role="alert">
        <p class="font-bold">Berhasil!</p>
        <p>{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
     <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg" role="alert">
        <p class="font-bold">Gagal!</p>
        <p>{{ session('error') }}</p>
    </div>
@endif