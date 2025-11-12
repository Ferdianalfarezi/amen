<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('drawing_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drawing_id')->constrained()->onDelete('cascade');
            $table->enum('category', [
                'sample_part',
                'quality', 
                'setup_procedure',
                'quotes',
                'work_instruction'
            ])->index();
            $table->enum('file_type', ['foto', 'video', 'dokumen'])->index();
            $table->string('nama');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('ukuran')->comment('File size in bytes');
            $table->text('deskripsi')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Index untuk query optimization
            $table->index(['drawing_id', 'category']);
            $table->index(['drawing_id', 'file_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drawing_files');
    }
};