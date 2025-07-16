<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang di SIADIG Inspektorat Trenggalek</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
    body {
        font-family: 'Inter', sans-serif;
        /* REVISI 1: Mengganti background pola menjadi gradien halus */
        background-color: #f8fafc;
        /* Warna fallback */
        background-image: linear-gradient(to bottom, #ffffff, #f1f5f9);
    }
    </style>
</head>

<body class="antialiased text-slate-700">
    <div class="container mx-auto px-6 py-12 md:py-20">

        <header class="text-center max-w-3xl mx-auto">
            <a href="/" class="inline-block mb-4">
                <x-application-logo class="w-16 h-16 text-gray-400" />
            </a>
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 tracking-tight">
                Portal Layanan Digital SIADIG
            </h1>
            <p class="mt-4 text-lg text-slate-500">
                Platform terintegrasi untuk mendukung tata kelola pengawasan yang akuntabel dan transparan bagi
                Inspektorat Kabupaten Trenggalek.
            </p>
            <a href="#layanan"
                class="mt-8 inline-block bg-slate-800 text-white font-semibold px-8 py-3 rounded-lg shadow-lg hover:bg-slate-700 transition-colors duration-300">
                Pilih Layanan Anda
            </a>
        </header>

        <main id="layanan" class="mt-16 md:mt-24">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                @php
                $cardClass = "block p-8 bg-white/80 backdrop-blur-sm border border-gray-100 rounded-xl text-center
                shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-300";
                @endphp

                <a href="{{ route('login') }}" class="{{ $cardClass }}">
                    <div
                        class="w-16 h-16 mx-auto bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-800">Login Internal</h3>
                    <p class="mt-1 text-sm text-slate-500">Akses untuk staf & pegawai Inspektorat.</p>
                </a>

                <a href="{{ route('login.external') }}" class="{{ $cardClass }}">
                    <div
                        class="w-16 h-16 mx-auto bg-teal-100 text-teal-600 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-800">Klien Eksternal</h3>
                    <p class="mt-1 text-sm text-slate-500">Portal untuk OPD/Desa merespons permintaan.</p>
                </a>

                <a href="{{ route('skbt.landing') }}" class="{{ $cardClass }}">
                    <div
                        class="w-16 h-16 mx-auto bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-800">Layanan SKBT</h3>
                    <p class="mt-1 text-sm text-slate-500">Pengajuan Surat Keterangan Bebas Temuan.</p>
                </a>

                <a href="https://inspektorat.trenggalekkab.go.id/" target="_blank" class="{{ $cardClass }}">
                    <div
                        class="w-16 h-16 mx-auto bg-amber-100 text-amber-600 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold text-slate-800">Website Utama</h3>
                    <p class="mt-1 text-sm text-slate-500">Informasi publik dan berita terbaru.</p>
                </a>
            </div>
        </main>

        @include('layouts.partials.footer')
        @stack('modals')

    </div>
</body>
</html>