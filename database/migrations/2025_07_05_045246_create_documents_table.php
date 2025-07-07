<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('document_categories');
            $table->string('document_number')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('document_date');
            $table->enum('status', ['Menunggu Review', 'Diarsip', 'Didisposisi', 'Ditolak', 'INAKTIF', 'Permanen'])->default('Menunggu Review');
            $table->enum('classification', ['Umum', 'Biasa', 'Rahasia'])->default('Biasa');
            $table->string('original_filename');
            $table->string('stored_path');
            $table->integer('file_size');
            $table->longText('ocr_text')->nullable();
            $table->string('physical_location_building', 100)->nullable();
            $table->string('physical_location_cabinet', 100)->nullable();
            $table->string('physical_location_rack', 100)->nullable();
            $table->string('physical_location_box', 100)->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('documents');
    }
};