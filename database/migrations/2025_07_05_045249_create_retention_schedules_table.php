<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('retention_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->unique()->constrained('document_categories')->onDelete('cascade');
            $table->integer('active_period_years')->default(0);
            $table->integer('inactive_period_years')->default(0);
            $table->text('description')->nullable();
        });
    }
    public function down(): void {
        Schema::dropIfExists('retention_schedules');
    }
};