<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('document_categories', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('is_for_client');
            // Tambah kolom baru
            $table->enum('scope', ['internal', 'external'])->default('internal')->after('description');
        });
    }

    public function down(): void {
        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropColumn('scope');
            $table->boolean('is_for_client')->default(false);
        });
    }
};