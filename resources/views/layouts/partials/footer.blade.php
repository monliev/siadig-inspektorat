<footer class="mt-16 md:mt-24 pt-8 border-t border-slate-200 text-center">
    <h4 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Tautan Penting</h4>
    <div
        class="mt-4 flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-6 text-gray-500">

        {{-- REVISI 2: Menambahkan ikon pada setiap link --}}
        <a href="http://inspektorat.trenggalekkab.go.id/pengaduan/"
            class="flex items-center hover:text-slate-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-2.236 9.168-5.584C18.354 1.86 17.17 1 16.002 1H7.994a4.002 4.002 0 00-3.994 4.002v.725a4 4 0 00.436 1.958z" />
            </svg>
            <span>WBS (Pengaduan)</span>
        </a>

        <a href="https://wa.me/6285158098090" class="flex items-center hover:text-slate-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>WhatsApp Pengaduan</span>
        </a>

        <a href="https://www.instagram.com/inspektorat_trenggalek"
            class="flex items-center hover:text-slate-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.52 19c.64-.64 1.49-1 2.48-1h7c.99 0 1.84.36 2.48 1M12 14a4 4 0 100-8 4 4 0 000 8z" />
            </svg>
            <span>Instagram</span>
        </a>

        <a href="{{ route('help.index') }}" class="flex items-center hover:text-slate-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Pusat Bantuan</span>
        </a>
    </div>
    <p class="mt-8 text-sm text-gray-400">&copy; {{ date('Y') }} Inspektorat Kabupaten Trenggalek. All rights reserved.
    </p>
</footer>