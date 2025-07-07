<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('document_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('related_document_id')->constrained('documents')->onDelete('cascade');
            $table->string('relation_type', 100);
        });
    }
    public function down(): void {
        Schema::dropIfExists('document_relations');
    }
};