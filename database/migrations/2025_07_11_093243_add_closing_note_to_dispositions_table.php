<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('dispositions', function (Blueprint $table) {
            $table->text('closing_note')->nullable()->after('status');
        });
    }

    public function down(): void {
        Schema::table('dispositions', function (Blueprint $table) {
            $table->dropColumn('closing_note');
        });
    }
};