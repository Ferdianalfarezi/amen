<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files_2d', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drawing_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('file_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('tipe_file', 10);
            $table->string('mime_type', 100);
            $table->bigInteger('ukuran');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files_2d');
    }
};