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
        // Langkah 1: Hapus foreign key dan kolom
        Schema::table('dispositions', function (Blueprint $table) {
            $table->dropForeign(['to_user_id']);  // <- penting
            $table->dropColumn('to_user_id');
        });
    
        // Langkah 2: Buat pivot table
        Schema::create('disposition_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disposition_id')->constrained('dispositions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposition_user');
        
        Schema::table('dispositions', function (Blueprint $table) {
            $table->foreignId('to_user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }
};