<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legalisirs', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('jenis', 20); // ijazah | rapor
            $table->unsignedTinyInteger('jumlah_rangkap')->default(1);
            $table->text('keperluan');
            $table->string('status', 20)->default('pending'); // pending|approved|rejected|selesai
            $table->text('catatan_admin')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tgl_selesai')->nullable();
            $table->string('diterima_oleh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legalisirs');
    }
};
