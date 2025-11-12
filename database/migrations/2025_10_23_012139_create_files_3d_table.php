<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files_3d', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drawing_id')->constrained()->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->string('file_path');
            $table->string('tipe_file', 10);
            $table->unsignedBigInteger('ukuran')->nullable(); // file size in bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files_3d');
    }
};