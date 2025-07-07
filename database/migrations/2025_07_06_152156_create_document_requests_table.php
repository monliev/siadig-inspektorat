<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->date('due_date')->nullable();
            $table->enum('status', ['Aktif', 'Ditutup'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('document_requests');
    }
};