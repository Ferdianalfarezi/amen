<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel 1: Qualities
        Schema::create('qualities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drawing_id');
            $table->string('nama');
            $table->string('file_path');
            $table->string('tipe_file', 10);
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('ukuran');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('drawing_id')->references('id')->on('drawings')->onDelete('cascade');
        });

        // Tabel 2: Sample Parts
        Schema::create('sample_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drawing_id');
            $table->string('nama');
            $table->string('file_path');
            $table->string('tipe_file', 10);
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('ukuran');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('drawing_id')->references('id')->on('drawings')->onDelete('cascade');
        });

        // Tabel 3: Setup Procedures
        Schema::create('setup_procedures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drawing_id');
            $table->string('nama');
            $table->string('file_path');
            $table->string('tipe_file', 10);
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('ukuran');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('drawing_id')->references('id')->on('drawings')->onDelete('cascade');
        });

        // Tabel 4: Quotes
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drawing_id');
            $table->string('nama');
            $table->string('file_path');
            $table->string('tipe_file', 10);
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('ukuran');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('drawing_id')->references('id')->on('drawings')->onDelete('cascade');
        });

        // Tabel 5: Work Instructions
        Schema::create('work_instructions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drawing_id');
            $table->string('nama');
            $table->string('file_path');
            $table->string('tipe_file', 10);
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('ukuran');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('drawing_id')->references('id')->on('drawings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_instructions');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('setup_procedures');
        Schema::dropIfExists('sample_parts');
        Schema::dropIfExists('qualities');
    }
};