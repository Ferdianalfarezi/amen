<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom baru.
     */
    public function up(): void
    {
        Schema::table('drawings', function (Blueprint $table) {
            $table->year('tahun_project')->nullable()->after('user_id');
            $table->string('customer')->nullable()->after('tahun_project');
            $table->string('project')->nullable()->after('customer');
            $table->string('departemen')->nullable()->after('project');
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('drawings', function (Blueprint $table) {
            $table->dropColumn(['tahun_project', 'customer', 'project', 'departemen']);
        });
    }
};
