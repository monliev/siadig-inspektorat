<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('document_request_id')
                  ->nullable()
                  ->after('uploaded_by')
                  ->constrained('document_requests')
                  ->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['document_request_id']);
            $table->dropColumn('document_request_id');
        });
    }
};