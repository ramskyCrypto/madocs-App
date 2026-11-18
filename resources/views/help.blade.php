@extends('layouts.app')
@section('title', 'Panduan Penggunaan')
@section('subtitle', 'Penjelasan singkat untuk pemula. Pilih peran Anda, lalu ikuti langkah-langkahnya.')
@section('content')
@php
    $role = auth()->user()->role;
    $siswa = [
        ['Meminjam buku rapor fisik', ['Buka menu Peminjaman Rapor, lalu tekan Ajukan Peminjaman.', 'Pilih dokumen, tanggal pinjam, rencana kembali (maksimal 14 hari), dan tulis tujuannya.', 'Tekan Kirim Pengajuan. Status awalnya Menunggu verifikasi.', 'Setelah Disetujui, ambil dokumen ke ruang Tata Usaha dan kembalikan sebelum tanggal rencana kembali.']],
        ['Meminta legalisir', ['Buka menu Legalisir, lalu tekan Ajukan Legalisir.', 'Pilih Ijazah atau Rapor, jumlah rangkap (1 sampai 10), dan tulis keperluannya.', 'Tunggu persetujuan petugas. Jika Disetujui, ambil dokumen di ruang Tata Usaha.']],
        ['Mengambil ijazah asli', ['Buka menu Pengambilan Ijazah, lalu tekan Jadwalkan Pengambilan.', 'Pilih tanggal (hari kerja) dan jam. Jika diwakilkan, centang Diwakilkan orang lain dan isi data pengambil.', 'Setelah Disetujui, Anda mendapat kode validasi di halaman detail dan Beranda.', 'Datang sesuai jadwal, bawa KTP atau kartu pelajar, dan sebutkan kode validasi kepada petugas.']],
    ];
    $staf = [
        ['Memverifikasi pengajuan', ['Buka Kotak Masuk. Pengajuan yang mendesak ada di paling atas.', 'Klik satu baris untuk membuka detail, lalu periksa datanya.', 'Pada kotak Verifikasi, pilih Disetujui atau Ditolak. Jika menolak, tulis alasannya di catatan, lalu tekan Simpan keputusan.']],
        ['Mencatat pengembalian atau penyerahan', ['Buka pengajuan yang berstatus Disetujui dari Kotak Masuk.', 'Peminjaman: tekan Dokumen Telah Dikembalikan. Legalisir: isi nama penerima lalu tekan Dokumen Telah Diserahkan.', 'Ijazah asli: minta kode validasi dan identitas pengambil, isi kolomnya, lalu tekan Validasi & Serahkan. Tindakan ini tidak dapat dibatalkan.']],
        ['Mengelola dokumen dan laporan', ['Repositori Dokumen: tekan Tambah, pilih siswa dan jenis dokumen, unggah berkas PDF/JPG/PNG maksimal 5 MB.', 'Laporan: pilih jenis laporan dan rentang tanggal, lalu cetak atau ekspor ke PDF/Excel (CSV).']],
    ];
    $faq = [
        ['Apa arti status Menunggu verifikasi?', 'Pengajuan sudah terkirim dan sedang menunggu diperiksa petugas. Anda tidak perlu mengirim ulang.'],
        ['Apa arti Disetujui?', 'Pengajuan diterima. Langkah berikutnya tergantung layanan: ambil dokumen (peminjaman dan legalisir) atau datang sesuai jadwal dengan kode validasi (ijazah).'],
        ['Pengajuan saya Ditolak, apa yang harus dilakukan?', 'Baca catatan petugas pada halaman detail, perbaiki data atau keperluannya, lalu buat pengajuan baru.'],
        ['Bagaimana membatalkan pengajuan?', 'Selama statusnya masih Menunggu verifikasi, buka halaman detail lalu tekan Batalkan.'],
        ['Saya lupa password.', 'Hubungi admin sekolah untuk mengatur ulang. Setelah masuk, ganti password lewat menu Profil.'],
    ];
    $blok = in_array($role, ['siswa']) ? [['Panduan untuk siswa', $siswa]] : [['Panduan untuk petugas dan admin', $staf]];
@endphp

@foreach($blok as [$judul, $daftar])
<div class="space-y-3">
    <h2 class="text-sm font-medium text-mute">{{ $judul }}</h2>
    @foreach($daftar as [$t, $langkah])
        <div class="card p-5">
            <h3 class="font-medium">{{ $t }}</h3>
            <ol class="mt-3 space-y-2.5">
                @foreach($langkah as $i => $l)
                    <li class="flex gap-3 text-sm"><span class="grid h-5 w-5 shrink-0 place-items-center rounded-full bg-brand-soft text-[11px] font-semibold text-brand-text">{{ $i + 1 }}</span><span class="min-w-0">{{ $l }}</span></li>
                @endforeach
            </ol>
        </div>
    @endforeach
</div>
@endforeach

<div class="space-y-3">
    <h2 class="text-sm font-medium text-mute">Pertanyaan yang sering muncul</h2>
    <div class="card divide-y divide-line">
        @foreach($faq as [$q, $a])
            <details class="group p-4">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-3 text-sm font-medium">{{ $q }}<x-icon name="plus" class="h-4 w-4 shrink-0 text-mute transition group-open:rotate-45" /></summary>
                <p class="mt-2 text-sm text-mute">{{ $a }}</p>
            </details>
        @endforeach
    </div>
</div>

<x-callout type="tip" title="Ingin melihat panduan singkat di Beranda lagi?">
    <button type="button" class="font-medium text-brand-text underline" onclick="mdStore.set('md_guide_{{ $role }}',''); location.href='{{ route('dashboard') }}'">Tampilkan kembali panduan di Beranda</button>
</x-callout>
@endsection
