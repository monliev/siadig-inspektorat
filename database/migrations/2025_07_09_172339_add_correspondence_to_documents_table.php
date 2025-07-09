<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('documents', function (Blueprint $table) {
            $table->foreignId('from_entity_id')->nullable()->after('document_request_id')->constrained('entities')->onDelete('set null');
            $table->foreignId('to_entity_id')->nullable()->after('from_entity_id')->constrained('entities')->onDelete('set null');
            $table->string('external_sender_name')->nullable()->after('to_entity_id');
        });
    }

    public function down(): void {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['from_entity_id']);
            $table->dropForeign(['to_entity_id']);
            $table->dropColumn(['from_entity_id', 'to_entity_id', 'external_sender_name']);
        });
    }
};