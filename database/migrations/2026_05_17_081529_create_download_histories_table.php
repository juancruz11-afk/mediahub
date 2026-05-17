<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('download_histories', function (Blueprint $table) {
            $table->id();
            $table->uuid('job_id')->unique();
            $table->text('url');
            $table->enum('format', ['mp4', 'mp3']);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('file_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_histories');
    }
};