# SIADIG - Sistem Informasi Administrasi Digital Inspektorat

Selamat datang di repository SIADIG, sebuah aplikasi web yang dirancang untuk mendigitalisasi dan mengoptimalkan alur kerja administrasi di lingkungan Inspektorat.

![Screenshot Dashboard](https://i.ibb.co/wN4VLRJ5/image.png) 
*(Catatan: Ganti URL di atas dengan link ke salah satu screenshot aplikasi Anda yang sudah diunggah)*

## 🚀 Latar Belakang

Aplikasi ini dibangun untuk mengatasi tantangan dalam manajemen dokumen, disposisi, dan layanan publik di Inspektorat Kabupaten Trenggalek. Tujuannya adalah untuk menciptakan sistem yang terpusat, transparan, dan efisien, mengurangi penggunaan kertas dan mempercepat proses birokrasi.

## ✨ Fitur Utama

Aplikasi ini memiliki tiga portal utama yang melayani kelompok pengguna yang berbeda:

1.  **Portal Internal (Inspektorat):**
    * **Manajemen Arsip:** Mengelola semua dokumen dan arsip internal secara digital.
    * **Disposisi Multi-Penerima:** Mengirimkan instruksi atau edaran ke banyak pegawai, unit kerja (Irban), atau tim khusus secara bersamaan.
    * **Manajemen Pengguna & Peran:** Mengatur hak akses untuk setiap pegawai (Super Admin, Admin Arsip, Pejabat Struktural, Auditor).
    * **Permintaan Dokumen ke OPD:** Membuat dan melacak permintaan dokumen resmi ke entitas eksternal.
    * **Log Viewer:** Memantau log teknis dan error aplikasi langsung dari antarmuka web (khusus Super Admin).

2.  **Portal Klien Eksternal (OPD/Desa):**
    * Sebuah halaman login khusus bagi OPD dan Desa untuk menerima permintaan dokumen dari Inspektorat dan mengunggah dokumen balasan.

3.  **Portal Layanan Publik (Permohonan SKBT):**
    * **Registrasi Mandiri:** Fitur bagi PNS untuk mendaftar dan membuat akun.
    * **Pengajuan Online:** Mengajukan permohonan Surat Keterangan Bebas Temuan (SKBT) dengan mengunggah dokumen persyaratan.
    * **Pelacakan Status:** Pemohon dapat melihat progres permohonan mereka secara real-time (Baru, Butuh Revisi, Selesai).
    * **Notifikasi WhatsApp:** Pemohon dan verifikator internal akan menerima notifikasi otomatis untuk setiap pembaruan status.

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP, Laravel Framework
* **Frontend:** Blade, Tailwind CSS, Alpine.js
* **Database:** MySQL
* **Notifikasi:** WAHA (WhatsApp HTTP API) Self-Hosted via Docker

## ⚙️ Instalasi & Setup Lokal

Untuk menjalankan proyek ini di lingkungan lokal, ikuti langkah-langkah berikut:

1.  **Clone Repository:**
    ```bash
    git clone [https://github.com/monliev/siadig-inspektorat.git](https://github.com/monliev/siadig-inspektorat.git)
    cd siadig
    ```

2.  **Install Dependensi:**
    ```bash
    composer install
    npm install
    ```

3.  **Setup Lingkungan:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    * *Sesuaikan konfigurasi database (`DB_*`) dan WAHA (`WAHA_*`) di dalam file `.env`.*

4.  **Migrasi & Seeding Database:**
    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Compile Aset & Jalankan Server:**
    ```bash
    npm run dev
    php artisan serve
    ```

## 📄 Lisensi

Proyek ini dikembangkan untuk Inspektorat Kabupaten Trenggalek dan bersifat internal.

---