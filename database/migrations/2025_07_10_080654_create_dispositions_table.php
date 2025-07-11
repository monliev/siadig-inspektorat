<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('dispositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
            $table->text('instructions');
            $table->enum('status', ['Terkirim', 'Dibaca', 'Ditindaklanjuti'])->default('Terkirim');
            
            // Kolom untuk Magic Link
            $table->string('response_token')->unique()->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('token_used_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('dispositions');
    }
};