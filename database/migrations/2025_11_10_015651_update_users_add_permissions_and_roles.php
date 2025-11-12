<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah kolom role untuk support 'admin'
            $table->enum('role', ['superadmin', 'admin', 'user'])->default('user')->change();
            
            // Tambah kolom permissions (JSON)
            $table->json('permissions')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['superadmin', 'user'])->default('user')->change();
            $table->dropColumn('permissions');
        });
    }
};