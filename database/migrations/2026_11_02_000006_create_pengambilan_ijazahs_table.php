<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengambilan_ijazahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('jadwal_tanggal');
            $table->string('jadwal_jam', 20);
            $table->boolean('diwakilkan')->default(false);
            $table->string('nama_pengambil')->nullable();
            $table->string('hubungan', 50)->nullable();
            $table->string('no_identitas', 30)->nullable();
            $table->text('catatan')->nullable();
            $table->string('status', 20)->default('pending'); // pending|approved|rejected|diserahkan
            $table->string('kode_validasi', 10)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tgl_diserahkan')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->string('no_identitas_penerima', 30)->nullable();
            $table->foreignId('diserahkan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengambilan_ijazahs');
    }
};
