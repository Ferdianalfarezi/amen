<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('converted_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drawing_id')->constrained()->onDelete('cascade');
            $table->foreignId('file_3d_id')->nullable()->constrained('files_3d')->onDelete('cascade'); // SESUAIKAN dengan nama tabel
            $table->string('original_filename');
            $table->string('original_path');
            $table->string('converted_filename');
            $table->string('converted_path');
            $table->string('original_type')->default('igs');
            $table->string('converted_type')->default('glb');
            $table->bigInteger('original_size')->default(0);
            $table->bigInteger('converted_size')->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('converted_files');
    }
};