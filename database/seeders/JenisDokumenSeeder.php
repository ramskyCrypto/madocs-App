<?php

namespace Database\Seeders;

use App\Models\JenisDokumen;
use Illuminate\Database\Seeder;

class JenisDokumenSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['IJZ', 'Ijazah', 'Ijazah asli / salinan digital'],
            ['RPR', 'Buku Rapor', 'Buku rapor per tahun ajaran'],
            ['SKL', 'Surat Keterangan Lulus', 'SKL sementara'],
            ['TRN', 'Transkrip Nilai', 'Rekap nilai seluruh semester'],
            ['SKH', 'SKHUN / Sertifikat', 'Sertifikat dan dokumen pendukung lain'],
        ];

        foreach ($data as [$kode, $nama, $ket]) {
            JenisDokumen::create(compact('kode', 'nama') + ['keterangan' => $ket]);
        }
    }
}
