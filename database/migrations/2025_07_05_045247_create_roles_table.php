<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('guard_name')->default('web');
            $table->text('description')->nullable();
            $table->timestamps(); // <-- TAMBAHKAN BARIS INI
        });
    }
    public function down(): void {
        Schema::dropIfExists('roles');
    }
};