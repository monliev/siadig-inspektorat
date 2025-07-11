<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dispositions', function (Blueprint $table) {
            // Ubah kolom status dengan pilihan baru
            $table->enum('status', ['Terkirim', 'Dibaca', 'Dibalas', 'Selesai'])
                  ->default('Terkirim')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dispositions', function (Blueprint $table) {
            // Kembalikan ke status lama jika di-rollback
            $table->enum('status', ['Terkirim', 'Dibaca', 'Ditindaklanjuti'])
                  ->default('Terkirim')->change();
        });
    }
};