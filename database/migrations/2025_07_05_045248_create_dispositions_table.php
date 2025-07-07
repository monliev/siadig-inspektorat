<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users');
            $table->foreignId('to_user_id')->constrained('users');
            $table->text('notes');
            $table->enum('status', ['Terkirim', 'Dilihat', 'Selesai'])->default('Terkirim');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('dispositions');
    }
};