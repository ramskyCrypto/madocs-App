<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['name' => 'Administrator', 'email' => 'admin@madocs.test', 'password' => 'password', 'role' => 'admin']);
        User::create(['name' => 'Petugas Tata Usaha', 'email' => 'petugas@madocs.test', 'password' => 'password', 'role' => 'petugas', 'phone' => '081234567890']);

        $siswa = [
            ['Ahmad Fauzi', '2021001', 'XII RPL 1'],
            ['Budi Santoso', '2021002', 'XII RPL 1'],
            ['Citra Lestari', '2021003', 'XII TKJ 1'],
            ['Dewi Anggraini', '2021004', 'XII TKJ 1'],
            ['Eko Prasetyo', '2021005', 'XII MM 1'],
        ];

        foreach ($siswa as $i => [$nama, $nis, $kelas]) {
            User::create([
                'name' => $nama,
                'email' => 'siswa'.($i + 1).'@madocs.test',
                'password' => 'password',
                'role' => 'siswa',
                'nis' => $nis,
                'kelas' => $kelas,
                'jurusan' => str_contains($kelas, 'RPL') ? 'Rekayasa Perangkat Lunak' : (str_contains($kelas, 'TKJ') ? 'Teknik Komputer & Jaringan' : 'Multimedia'),
                'tahun_lulus' => '2026',
                'phone' => '0812000000'.($i + 1),
                'address' => 'Surakarta, Jawa Tengah',
            ]);
        }
    }
}
