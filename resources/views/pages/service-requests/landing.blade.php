<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Layanan SKBT - SIADIG Inspektorat Trenggalek</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }
    </style>
</head>

<body class="antialiased bg-gray-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto py-12 sm:py-16">

            <div class="text-center">
                <a href="{{ route('portal') }}">
                    <x-application-logo class="w-16 h-16 mx-auto text-gray-400" />
                </a>
                <h1 class="mt-4 text-3xl font-extrabold text-slate-800 tracking-tight sm:text-4xl">
                    Layanan Permohonan Surat Keterangan Bebas Temuan (SKBT)
                </h1>
                <p class="mt-4 text-lg text-slate-600">
                    Informasi dan alur pengajuan surat keterangan bebas temuan bagi Aparatur Sipil Negara (ASN) di
                    lingkungan Pemerintah Kabupaten Trenggalek.
                </p>
            </div>

            <div class="mt-12 bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-6 sm:p-8">
                <h2 class="text-xl font-bold text-slate-800 border-b pb-3 mb-4">Standar Pelayanan</h2>

                <div class="space-y-6">
                    <div>
                        <h3 class="font-semibold text-slate-700">Persyaratan Dokumen</h3>
                        <ol class="list-decimal list-inside mt-2 text-slate-600 space-y-1">
                            <li>Surat Permohonan yang ditujukan kepada Inspektur Daerah.</li>
                            <li>Surat Pernyataan Bebas Temuan dari Kepala OPD yang ditinggalkan.</li>
                            <li>Surat Keterangan/Rekomendasi Menerima dari OPD yang dituju.</li>
                            <li>Fotokopi SK CPNS.</li>
                            <li>Fotokopi SK PNS.</li>
                            <li>Fotokopi SK Pangkat terakhir.</li>
                            <li>Fotokopi SKP satu tahun terakhir.</li>
                            <li>Surat Pernyataan tidak pernah dijatuhi tindakan disiplin atau tindakan kriminal.</li>
                            <li>Surat Keterangan Meninggal (bagi yang meninggal dunia).</li>
                        </ol>
                    </div>

                    <div>
                        <h3 class="font-semibold text-slate-700">Dasar Penerbitan</h3>
                        <p class="mt-1 text-slate-600">Surat Keterangan Bebas Temuan didasarkan kepada ASN atau Rekanan
                            yang tidak memiliki temuan terkait Pemeriksaan Inspektorat, Pemeriksaan BPK RI, dan
                            Pemeriksaan Inspektorat Jenderal Kementerian Dalam Negeri.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h3 class="font-semibold text-slate-700">Waktu Pelayanan</h3>
                            <p class="mt-1 text-slate-600">1 - 5 hari kerja.</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-700">Biaya</h3>
                            <p class="mt-1 text-slate-600 font-bold">Tidak Dipungut Biaya.</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-700">Produk Layanan</h3>
                            <p class="mt-1 text-slate-600">Surat Keterangan Bebas Temuan (Digital).</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-semibold text-slate-700">Informasi & Pengaduan</h3>
                        <p class="mt-1 text-slate-600">Untuk informasi lebih lanjut atau pengaduan layanan, silakan
                            hubungi kami melalui email: <a href="mailto:inspektorat@trenggalekkab.go.id"
                                class="text-indigo-600 hover:underline">inspektorat@trenggalekkab.go.id</a></p>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('login.skbt') }}"
                    class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-indigo-600 bg-indigo-100 hover:bg-indigo-200">
                    Login (Jika Sudah Punya Akun)
                </a>
                <a href="{{ route('register') }}"
                    class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Registrasi & Ajukan Permohonan
                </a>
            </div>

        </div>
    </div>
</body>

</html>