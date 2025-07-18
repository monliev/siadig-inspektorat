<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['OPD', 'Kecamatan', 'Desa']);
            $table->foreignId('parent_id')->nullable()->constrained('entities')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('entities');
    }
};