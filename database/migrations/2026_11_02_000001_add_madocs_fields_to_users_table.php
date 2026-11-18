<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('siswa')->after('password');
            $table->string('nis', 30)->nullable()->unique()->after('role');
            $table->string('kelas', 30)->nullable()->after('nis');
            $table->string('jurusan', 50)->nullable()->after('kelas');
            $table->string('tahun_lulus', 4)->nullable()->after('jurusan');
            $table->string('phone', 20)->nullable()->after('tahun_lulus');
            $table->text('address')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nis', 'kelas', 'jurusan', 'tahun_lulus', 'phone', 'address', 'is_active']);
        });
    }
};
