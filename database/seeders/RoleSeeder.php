<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; // <-- Panggil Model Role

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama untuk menghindari duplikat
        Role::query()->delete();

        // Masukkan data role baru
        Role::create([
            'name' => 'Super Admin',
            'description' => 'Akses penuh ke seluruh sistem.'
        ]);

        Role::create([
            'name' => 'Admin Arsip',
            'description' => 'Mengelola dan memvalidasi semua arsip.'
        ]);

        Role::create([
            'name' => 'Pejabat Struktural',
            'description' => 'Inspektur, Sekretaris, Irban. Bisa melakukan disposisi.'
        ]);

        Role::create([
            'name' => 'Auditor',
            'description' => 'Mengunggah LHP dan dokumen pemeriksaan.'
        ]);

        Role::create([
            'name' => 'Pegawai',
            'description' => 'Akses terbatas untuk melihat dan mengunggah dokumen umum.'
        ]);
    }
}