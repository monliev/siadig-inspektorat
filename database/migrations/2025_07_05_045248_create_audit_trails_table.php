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
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');

            // Baris ini adalah kunci perbaikannya.
            // Kita secara eksplisit membuat kolom yang bisa NULL sebelum mendefinisikan constraint.
            $table->foreignId('document_id')
                  ->nullable() // Pastikan kolom ini bisa NULL
                  ->constrained('documents') // Hubungkan ke tabel documents
                  ->onDelete('set null'); // Jika dokumen induk dihapus, set kolom ini ke NULL

            $table->string('action', 50);
            $table->text('description');
            $table->ipAddress()->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};