<x-guest-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            <div class="text-center mb-12">
                <a href="{{ route('portal') }}">
                    <x-application-logo class="w-16 h-16 mx-auto text-gray-400" />
                </a>
                <h1 class="mt-4 text-4xl font-extrabold text-slate-800 tracking-tight sm:text-5xl">
                    Pusat Bantuan SIADIG
                </h1>
                <p class="mt-3 text-lg text-gray-600 max-w-2xl mx-auto">
                    Panduan terperinci, tips, dan jawaban atas pertanyaan umum untuk memaksimalkan penggunaan aplikasi
                    SIADIG.
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 md:p-8 text-gray-900 space-y-16">

                    <div class="p-6 bg-slate-50 rounded-xl border border-slate-200">
                        <h3 class="font-semibold text-xl mb-4 text-slate-800">📖 Daftar Panduan</h3>
                        <ul class="space-y-2 text-indigo-700 font-medium">
                            <li><a href="#panduan-pemohon" class="hover:underline">1. Panduan untuk Pemohon (Layanan
                                    SKBT)</a></li>
                            <li><a href="#panduan-klien" class="hover:underline">2. Panduan untuk Klien Eksternal
                                    (OPD/Desa)</a></li>
                            <li><a href="#panduan-internal" class="hover:underline">3. Panduan untuk Pengguna Internal
                                    (Inspektorat)</a></li>
                            <li><a href="#faq" class="hover:underline">4. FAQ & Penyelesaian Masalah</a></li>
                            <li><a href="#glosarium" class="hover:underline">5. Glosarium Istilah</a></li>
                        </ul>
                    </div>

                    <section id="panduan-pemohon" class="space-y-8">
                        <h2 class="text-3xl font-bold text-slate-800 border-b pb-4">1. Panduan untuk Pemohon (Layanan
                            SKBT)</h2>
                        <p class="text-gray-600 leading-relaxed">Panduan lengkap ini ditujukan bagi Anda (Aparatur Sipil
                            Negara) yang ingin mengajukan permohonan Surat Keterangan Bebas Temuan (SKBT) secara online.
                        </p>

                        <div class="p-6 bg-white rounded-xl border">
                            <h3 class="font-semibold text-xl text-gray-800">1.1 Alur Lengkap Permohonan</h3>
                            <ol class="list-decimal list-inside mt-4 text-gray-700 space-y-4">
                                <li>
                                    <span class="font-semibold">Registrasi Akun:</span> Kunjungi Halaman Portal utama,
                                    pilih kotak "Layanan SKBT", lalu klik "Registrasi". Isi semua data dengan benar.
                                    <blockquote
                                        class="border-l-4 border-blue-400 bg-blue-50 text-blue-800 p-3 my-2 text-sm rounded-r-lg">
                                        <strong>💡 Tip:</strong> Gunakan alamat email dan nomor WhatsApp yang aktif.
                                        Semua notifikasi penting mengenai status permohonan Anda akan dikirimkan ke
                                        sana.
                                    </blockquote>
                                </li>
                                <li><span class="font-semibold">Login & Masuk Dashboard:</span> Setelah berhasil
                                    mendaftar dan login, Anda akan masuk ke Dashboard Pemohon. Halaman ini adalah pusat
                                    kendali Anda.</li>
                                <li>
                                    <span class="font-semibold">Ajukan Permohonan Baru:</span> Klik tombol "Ajukan
                                    Permohonan Bebas Temuan Baru". Anda akan diarahkan ke halaman formulir pengajuan.
                                </li>
                                <li>
                                    <span class="font-semibold">Unggah Dokumen Persyaratan:</span> Unggah setiap dokumen
                                    yang diminta satu per satu. Pastikan file jelas terbaca dan sesuai dengan jenis
                                    dokumen yang diminta.
                                    <blockquote
                                        class="border-l-4 border-yellow-400 bg-yellow-50 text-yellow-800 p-3 my-2 text-sm rounded-r-lg">
                                        <strong>⚠️ Penting:</strong> Ukuran maksimal per file adalah 2MB. Jika file PDF
                                        Anda terlalu besar, gunakan layanan kompresor PDF online sebelum mengunggah.
                                        Beri nama file yang jelas (contoh: `SK_PNS_NamaAnda.pdf`).
                                    </blockquote>
                                </li>
                                <li><span class="font-semibold">Kirim & Tunggu Verifikasi:</span> Setelah semua terisi,
                                    klik "Ajukan Permohonan". Status permohonan Anda kini "BARU" dan akan segera
                                    diperiksa oleh tim kami.</li>
                                <li>
                                    <span class="font-semibold">Proses Revisi:</span> Jika ada kekurangan, status akan
                                    berubah menjadi "BUTUH REVISI". Buka "Lihat Detail" pada permohonan Anda untuk
                                    membaca catatan dari auditor, lalu unggah dokumen perbaikan pada form yang tersedia.
                                </li>
                                <li><span class="font-semibold">Selesai & Unduh Surat:</span> Jika permohonan disetujui,
                                    status akan menjadi "SELESAI". Anda akan menerima notifikasi, dan tombol untuk
                                    mengunduh Surat Bebas Temuan akan muncul di halaman detail permohonan Anda.</li>
                            </ol>
                        </div>
                    </section>

                    <section id="panduan-klien" class="space-y-6">
                        <h2 class="text-3xl font-bold text-slate-800 border-b pb-4">2. Panduan untuk Klien Eksternal
                            (OPD/Desa)</h2>
                        <div class="p-6 bg-white rounded-xl border">
                            <h3 class="font-semibold text-xl text-gray-800">Alur Menanggapi Permintaan Dokumen</h3>
                            <p class="mt-2 text-gray-600">Panduan bagi pengguna dari OPD, Desa, atau instansi lain yang
                                menerima permintaan dokumen dari Inspektorat.</p>
                            <ol class="list-decimal list-inside mt-4 text-gray-700 space-y-2">
                                <li>Login ke sistem melalui Halaman Portal dengan memilih kotak "Klien Eksternal".</li>
                                <li>Di halaman Dashboard, Anda akan melihat daftar "Permintaan Dokumen" yang ditujukan
                                    kepada instansi Anda beserta statusnya.</li>
                                <li>Klik "Tanggapi" pada permintaan yang relevan untuk melihat rincian lengkapnya.</li>
                                <li>Gunakan formulir yang tersedia untuk mengunggah dan mengirim dokumen balasan yang
                                    diminta oleh Inspektorat.</li>
                                <blockquote
                                    class="border-l-4 border-blue-400 bg-blue-50 text-blue-800 p-3 my-2 text-sm rounded-r-lg">
                                    <strong>💡 Tip:</strong> Selalu berkomunikasi dengan narahubung di Inspektorat jika
                                    rincian permintaan kurang jelas untuk menghindari kesalahan pengiriman dokumen.
                                </blockquote>
                            </ol>
                        </div>
                    </section>

                    <section id="panduan-internal" class="space-y-6">
                        <h2 class="text-3xl font-bold text-slate-800 border-b pb-4">3. Panduan untuk Pengguna Internal
                        </h2>
                        <div class="p-6 bg-white rounded-xl border">
                            <h3 class="font-semibold text-xl text-gray-800">Deskripsi Fungsi Menu Utama</h3>
                            <p class="mt-2 text-gray-600">Penjelasan singkat mengenai fungsi dan kegunaan setiap modul
                                utama bagi staf Inspektorat.</p>
                            <ul class="list-disc list-inside mt-4 text-gray-700 space-y-4">
                                <li><b>Dashboard:</b> Ringkasan visual dari semua aktivitas penting, seperti jumlah
                                    dokumen masuk, disposisi yang belum dibaca, dan permohonan yang menunggu verifikasi.
                                </li>
                                <li><b>Arsip:</b> Pusat pengelolaan semua dokumen dan arsip digital internal. Gunakan
                                    fitur pencarian untuk menemukan arsip dengan cepat.</li>
                                <li><b>Disposisi:</b> Alat untuk mendelegasikan tugas atau memberikan instruksi terkait
                                    sebuah dokumen kepada staf lain secara tercatat dan terstruktur.</li>
                                <li><b>Permintaan Dokumen OPD:</b> Modul untuk mengirim permintaan dokumen resmi ke
                                    pihak eksternal dan memantau progres pengiriman balasan dari mereka.</li>
                                <li><b>Permohonan SKBT:</b> Ruang kerja utama untuk memproses permohonan SKBT. Lakukan
                                    verifikasi, minta revisi, atau setujui permohonan di sini.
                                    <blockquote
                                        class="border-l-4 border-blue-400 bg-blue-50 text-blue-800 p-3 my-2 text-sm rounded-r-lg">
                                        <strong>💡 Tip:</strong> Saat meminta revisi, berikan catatan yang jelas dan
                                        spesifik agar pemohon tidak bingung dan bisa segera mengirim perbaikan.
                                    </blockquote>
                                </li>
                                <li><b>Master Data:</b> Area khusus Admin untuk mengelola data inti aplikasi seperti
                                    pengguna, peran, hak akses, daftar OPD, dan jenis-jenis dokumen.</li>
                            </ul>
                        </div>
                    </section>

                    <section id="faq" class="space-y-6">
                        <h2 class="text-3xl font-bold text-slate-800 border-b pb-4">4. FAQ & Penyelesaian Masalah</h2>
                        <div class="space-y-4">
                            <details
                                class="p-4 bg-gray-50 rounded-lg border cursor-pointer group transition-all duration-300 open:bg-white open:shadow-md">
                                <summary class="font-semibold hover:text-gray-900">Bagaimana jika saya salah mengunggah
                                    dokumen saat mengajukan permohonan?</summary>
                                <p class="mt-3 text-gray-600 text-sm leading-relaxed">Jangan khawatir. Anda tidak bisa
                                    mengubahnya langsung, tetapi tim verifikator kami akan meninjau dokumen Anda. Jika
                                    ada yang salah, mereka akan mengubah status permohonan Anda menjadi "BUTUH REVISI"
                                    dan memberikan catatan. Anda akan mendapat notifikasi untuk mengunggah ulang dokumen
                                    yang benar.</p>
                            </details>
                            <details
                                class="p-4 bg-gray-50 rounded-lg border cursor-pointer group transition-all duration-300 open:bg-white open:shadow-md">
                                <summary class="font-semibold hover:text-gray-900">Apakah data pribadi dan dokumen saya
                                    aman di sistem ini?</summary>
                                <p class="mt-3 text-gray-600 text-sm leading-relaxed">Ya. Aplikasi SIADIG menggunakan
                                    standar keamanan modern, termasuk enkripsi koneksi (HTTPS) dan sistem hak akses yang
                                    ketat. Dokumen pribadi Anda hanya dapat diakses oleh Anda dan tim verifikator yang
                                    berwenang di Inspektorat.</p>
                            </details>
                            <details
                                class="p-4 bg-gray-50 rounded-lg border cursor-pointer group transition-all duration-300 open:bg-white open:shadow-md">
                                <summary class="font-semibold hover:text-gray-900">Status permohonan saya sudah lama
                                    tidak berubah, apa yang harus saya lakukan?</summary>
                                <p class="mt-3 text-gray-600 text-sm leading-relaxed">Waktu pelayanan standar kami
                                    adalah 1-5 hari kerja setelah dokumen dinyatakan lengkap. Jika melebihi waktu
                                    tersebut, Anda dapat menghubungi kami melalui kontak yang tersedia di Halaman
                                    Informasi Layanan SKBT untuk menanyakan status permohonan Anda dengan menyertakan ID
                                    Permohonan.</p>
                            </details>
                        </div>
                    </section>

                    <section id="glosarium">
                        <h2 class="text-3xl font-bold text-slate-800 border-b pb-4">5. Glosarium Istilah</h2>
                        <div
                            class="p-6 bg-white rounded-xl border text-sm grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                            <p><strong class="font-semibold text-slate-800">SKBT</strong>: Singkatan dari Surat
                                Keterangan Bebas Temuan.</p>
                            <p><strong class="font-semibold text-slate-800">OPD</strong>: Singkatan dari Organisasi
                                Perangkat Daerah.</p>
                            <p><strong class="font-semibold text-slate-800">NIP</strong>: Singkatan dari Nomor Induk
                                Pegawai.</p>
                            <p><strong class="font-semibold text-slate-800">SKP</strong>: Singkatan dari Sasaran Kinerja
                                Pegawai.</p>
                            <p><strong class="font-semibold text-slate-800">Disposisi</strong>: Perintah atau instruksi
                                singkat dari pimpinan pada sebuah dokumen untuk ditindaklanjuti oleh staf.</p>
                            <p><strong class="font-semibold text-slate-800">Verifikasi</strong>: Proses pemeriksaan
                                kelengkapan dan keabsahan dokumen yang diajukan.</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>