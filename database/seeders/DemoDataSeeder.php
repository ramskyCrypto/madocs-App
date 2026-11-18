<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\JenisDokumen;
use App\Models\Legalisir;
use App\Models\PengambilanIjazah;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $ijazah = JenisDokumen::where('kode', 'IJZ')->first();
        $rapor = JenisDokumen::where('kode', 'RPR')->first();
        $petugas = User::where('role', 'petugas')->first();
        $siswa = User::where('role', 'siswa')->get();

        $docs = [];
        foreach ($siswa as $s) {
            $docs[$s->id]['ijazah'] = Document::create([
                'user_id' => $s->id, 'jenis_dokumen_id' => $ijazah->id, 'judul' => 'Ijazah '.$s->name,
                'tahun_ajaran' => '2025/2026', 'ada_fisik' => true, 'lokasi_arsip' => 'Lemari A / Rak 1',
                'uploaded_by' => $petugas->id,
            ]);
            foreach (['X' => '2023/2024', 'XI' => '2024/2025', 'XII' => '2025/2026'] as $kls => $ta) {
                $docs[$s->id]['rapor'.$kls] = Document::create([
                    'user_id' => $s->id, 'jenis_dokumen_id' => $rapor->id, 'judul' => "Buku Rapor Kelas $kls",
                    'tahun_ajaran' => $ta, 'ada_fisik' => true, 'lokasi_arsip' => 'Lemari B / Rak '.rand(1, 5),
                    'uploaded_by' => $petugas->id,
                ]);
            }
        }

        $kode = fn ($p) => $p.'-'.now()->format('ymd').'-'.strtoupper(Str::random(4));
        [$s1, $s2, $s3, $s4, $s5] = $siswa->all();

        // Peminjaman
        Peminjaman::create(['kode' => $kode('PJM'), 'user_id' => $s1->id, 'document_id' => $docs[$s1->id]['raporXII']->id,
            'tgl_pinjam' => now()->addDay(), 'tgl_kembali_rencana' => now()->addDays(3), 'tujuan' => 'Melengkapi berkas pendaftaran kuliah', 'status' => 'pending']);

        $p2 = Peminjaman::create(['kode' => $kode('PJM'), 'user_id' => $s2->id, 'document_id' => $docs[$s2->id]['raporXI']->id,
            'tgl_pinjam' => now()->subDay(), 'tgl_kembali_rencana' => now()->addDays(2), 'tujuan' => 'Fotokopi untuk beasiswa', 'status' => 'approved', 'verified_by' => $petugas->id]);
        $p2->document->update(['status_fisik' => 'dipinjam']);

        Peminjaman::create(['kode' => $kode('PJM'), 'user_id' => $s4->id, 'document_id' => $docs[$s4->id]['raporX']->id,
            'tgl_pinjam' => now()->subDays(5), 'tgl_kembali_rencana' => now()->subDays(3), 'tujuan' => 'Keperluan pribadi', 'status' => 'rejected',
            'catatan_admin' => 'Tujuan peminjaman kurang jelas.', 'verified_by' => $petugas->id]);

        Peminjaman::create(['kode' => $kode('PJM'), 'user_id' => $s5->id, 'document_id' => $docs[$s5->id]['raporXII']->id,
            'tgl_pinjam' => now()->subDays(10), 'tgl_kembali_rencana' => now()->subDays(7), 'tgl_dikembalikan' => now()->subDays(7),
            'tujuan' => 'Verifikasi nilai', 'status' => 'dikembalikan', 'verified_by' => $petugas->id]);

        // Legalisir
        Legalisir::create(['kode' => $kode('LGL'), 'user_id' => $s3->id, 'jenis' => 'ijazah', 'jumlah_rangkap' => 3, 'keperluan' => 'Melamar kerja', 'status' => 'pending']);
        Legalisir::create(['kode' => $kode('LGL'), 'user_id' => $s1->id, 'jenis' => 'rapor', 'jumlah_rangkap' => 2, 'keperluan' => 'Daftar kuliah', 'status' => 'selesai',
            'verified_by' => $petugas->id, 'tgl_selesai' => now()->subDays(2), 'diterima_oleh' => $s1->name]);
        Legalisir::create(['kode' => $kode('LGL'), 'user_id' => $s2->id, 'jenis' => 'ijazah', 'jumlah_rangkap' => 5, 'keperluan' => 'Melamar kerja', 'status' => 'approved', 'verified_by' => $petugas->id]);

        // Pengambilan ijazah
        PengambilanIjazah::create(['kode' => $kode('IJZ'), 'user_id' => $s2->id, 'jadwal_tanggal' => now()->addDays(2), 'jadwal_jam' => '09:00 - 10:00',
            'diwakilkan' => false, 'status' => 'approved', 'kode_validasi' => 'MD'.rand(1000, 9999), 'verified_by' => $petugas->id]);
        PengambilanIjazah::create(['kode' => $kode('IJZ'), 'user_id' => $s3->id, 'jadwal_tanggal' => now()->addDays(4), 'jadwal_jam' => '10:00 - 11:00',
            'diwakilkan' => true, 'nama_pengambil' => 'Siti Aminah', 'hubungan' => 'Ibu', 'no_identitas' => '3372010101700001', 'status' => 'pending']);
    }
}
