<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('entities', function (Blueprint $table) {
            $table->string('agency_code')->nullable()->unique()->after('type');
        });
    }

    public function down(): void {
        Schema::table('entities', function (Blueprint $table) {
            $table->dropColumn('agency_code');
        });
    }
};