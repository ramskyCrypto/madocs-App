<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jenis_dokumen_id')->constrained('jenis_dokumens')->restrictOnDelete();
            $table->string('judul');
            $table->string('tahun_ajaran', 20)->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('ada_fisik')->default(false);
            $table->string('lokasi_arsip')->nullable();
            $table->string('status_fisik', 20)->default('tersedia'); // tersedia | dipinjam
            $table->text('keterangan')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
