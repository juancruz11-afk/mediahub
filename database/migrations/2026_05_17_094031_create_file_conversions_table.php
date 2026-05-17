<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_conversions', function (Blueprint $table) {
            $table->id();
            $table->uuid('job_id')->unique();
            $table->string('original_name');
            $table->string('stored_path'); // Donde guardamos el archivo subido
            $table->enum('conversion_type', ['pdf_to_img', 'img_to_pdf']);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('result_file')->nullable(); // El zip o pdf final
            $table->timestamps();
        });
    }
};