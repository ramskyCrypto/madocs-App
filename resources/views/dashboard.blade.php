@extends('layouts.app')
@section('title', 'Beranda')
@section('subtitle', 'Ringkasan layanan dokumen akademik. Mulai dari panduan singkat atau pilih layanan di bawah.')
@section('content')
@php
    $u = auth()->user();
    $staff = $u->isStaff();
    $first = explode(' ', trim($u->name))[0];
    $tint = [
        'Kotak masuk' => ['bg-brand-soft', 'text-brand-text', 'Pengajuan yang perlu diurus'],
        'Mendesak' => ['bg-bad-soft', 'text-bad-text', 'Terlambat atau jadwal hari ini'],
        'Dokumen fisik dipinjam' => ['bg-warn-soft', 'text-warn-text', 'Sedang dipinjam siswa'],
        'Siswa' => ['bg-ok-soft', 'text-ok-text', 'Akun siswa terdaftar'],
        'Dokumen saya' => ['bg-brand-soft', 'text-brand-text', 'Tersimpan di repositori'],
        'Peminjaman aktif' => ['bg-warn-soft', 'text-warn-text', 'Rapor yang sedang Anda pinjam'],
        'Menunggu verifikasi' => ['bg-ok-soft', 'text-ok-text', 'Menunggu diperiksa petugas'],
    ];
    $steps = $staff
        ? [['Buka Kotak Masuk', 'Semua pengajuan yang perlu diurus ada di sana, yang paling mendesak di atas.'], ['Periksa lalu putuskan', 'Pilih Disetujui atau Ditolak, tambahkan catatan bila perlu.'], ['Catat hasilnya', 'Setelah dokumen dikembalikan atau diserahkan, tekan tombol pencatatan.']]
        : [['Pilih layanan', 'Peminjaman rapor, legalisir, atau pengambilan ijazah.'], ['Isi formulir singkat', 'Cukup isi kolom bertanda bintang merah lalu kirim.'], ['Pantau statusnya', 'Petugas akan memeriksa. Hasilnya tampil di halaman layanan.']];
    $aksi = $staff
        ? array_filter([['Kotak Masuk', 'Verifikasi pengajuan baru', route('inbox.index'), 'inbox'], ['Tambah dokumen', 'Unggah dan arsipkan dokumen siswa', route('documents.create'), 'file'], ['Laporan', 'Cetak atau unduh rekap layanan', route('laporan.index'), 'chart'], $u->isAdmin() ? ['Pengguna', 'Kelola akun admin, petugas, siswa', route('users.index'), 'users'] : null])
        : [['Pinjam rapor fisik', 'Ajukan peminjaman buku rapor asli', route('peminjaman.create'), 'book'], ['Legalisir', 'Minta legalisir ijazah atau rapor', route('legalisir.create'), 'shield'], ['Ambil ijazah asli', 'Pilih jadwal dan dapatkan kode validasi', route('ijazah.create'), 'cap'], ['Dokumen saya', 'Lihat dan unduh dokumen digital', route('documents.index'), 'folder']];
@endphp

<div x-data="{ show: !(window.mdStore && mdStore.get('md_guide_{{ $u->role }}')) }" x-show="show" x-cloak class="card p-5">
    <div class="flex items-start justify-between gap-3">
        <div>
            <h2 class="text-base font-semibold">Halo, {{ $first }}! Mulai di sini</h2>
            <p class="mt-0.5 text-sm text-mute">{{ $staff ? 'Tiga langkah untuk mengurus pengajuan siswa.' : 'Tiga langkah untuk mengajukan layanan dokumen.' }}</p>
        </div>
        <button type="button" class="btn btn-ghost btn-sm" @click="show=false; mdStore.set('md_guide_{{ $u->role }}','1')">Sembunyikan</button>
    </div>
    <ol class="mt-4 grid gap-3 sm:grid-cols-3">
        @foreach($steps as $i => [$t, $d])
            <li class="flex gap-3 rounded-xl bg-soft p-3.5">
                <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-brand text-xs font-semibold text-white">{{ $i + 1 }}</span>
                <div class="min-w-0"><div class="text-sm font-medium">{{ $t }}</div><div class="mt-0.5 text-xs text-mute">{{ $d }}</div></div>
            </li>
        @endforeach
    </ol>
    <p class="mt-3 text-xs text-mute">Butuh penjelasan lebih lengkap? Buka <a href="{{ route('help') }}" class="font-medium text-brand-text underline">Panduan Penggunaan</a>.</p>
</div>

@isset($ijazahAktif)
    <div class="card flex flex-wrap items-center gap-4 border-brand/30 bg-brand-soft p-5">
        <div class="min-w-0 flex-1">
            <div class="text-sm font-medium text-brand-text">Pengambilan ijazah Anda sudah disetujui</div>
            <div class="mt-0.5 text-sm text-ink">Jadwal: {{ $ijazahAktif->jadwal_tanggal->translatedFormat('l, d F Y') }}, {{ $ijazahAktif->jadwal_jam }}. Tunjukkan kode ini kepada petugas.</div>
            <a href="{{ route('ijazah.show', $ijazahAktif) }}" class="mt-1 inline-block text-sm font-medium text-brand-text underline">Lihat detail</a>
        </div>
        <div class="rounded-xl bg-card px-5 py-3 text-center">
            <div class="text-[11px] uppercase tracking-wider text-mute">Kode validasi</div>
            <div class="font-mono text-2xl font-semibold tracking-widest text-brand-text">{{ $ijazahAktif->kode_validasi }}</div>
        </div>
    </div>
@endisset

<div class="grid grid-cols-2 gap-3 lg:grid-cols-{{ count($stats) === 4 ? '4' : '3' }}">
    @foreach($stats as $label => $val)
        @php [$bg, $fg, $cap] = $tint[$label] ?? ['bg-soft', 'text-ink', '']; @endphp
        <div class="rounded-2xl p-4 {{ $bg }}">
            <div class="text-sm font-medium {{ $fg }}">{{ $label }}</div>
            <div class="mt-1 text-3xl font-semibold tracking-tight {{ $fg }}">{{ $val }}</div>
            @if($cap)<div class="mt-0.5 text-xs text-mute">{{ $cap }}</div>@endif
        </div>
    @endforeach
</div>

<div>
    <h2 class="mb-2 text-sm font-medium text-mute">Pintasan</h2>
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-{{ count($aksi) }}">
        @foreach($aksi as [$t, $d, $url, $ic])
            <a href="{{ $url }}" class="card group flex items-start gap-3 p-4 transition hover:border-brand/50 hover:bg-soft">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-brand-soft text-brand-text"><x-icon :name="$ic" class="h-[18px] w-[18px]" /></span>
                <span class="min-w-0"><span class="block text-sm font-medium">{{ $t }}</span><span class="mt-0.5 block text-xs text-mute">{{ $d }}</span></span>
            </a>
        @endforeach
    </div>
</div>

@isset($inbox)
<div class="card overflow-hidden">
    <div class="flex items-center justify-between gap-2 border-b border-line px-4 py-3">
        <div class="flex items-center gap-2 font-medium">Kotak Masuk
            @if($inbox->count())<span class="rounded-full bg-brand px-2 py-0.5 text-xs text-white">{{ $inbox->count() }}</span>@endif
        </div>
        <a href="{{ route('inbox.index') }}" class="text-sm font-medium text-brand-text hover:underline">Lihat semua</a>
    </div>
    <div class="divide-y divide-line">
        @forelse($inbox->take(6) as $it)
            @include('partials.inbox-row', ['it' => $it])
        @empty
            <x-empty icon="check" title="Kotak masuk kosong">Semua pengajuan sudah diurus. Kerja bagus!</x-empty>
        @endforelse
    </div>
    @if($inbox->count() > 6)
        <a href="{{ route('inbox.index') }}" class="block border-t border-line bg-soft py-2.5 text-center text-sm text-mute hover:text-ink">+ {{ $inbox->count() - 6 }} lainnya</a>
    @endif
</div>
@endisset

<div class="card overflow-hidden">
    <div class="border-b border-line px-4 py-3 font-medium">Rekapitulasi layanan <span class="ml-1 text-xs font-normal text-mute">{{ $staff ? 'seluruh siswa' : 'pengajuan Anda' }}</span></div>
    <div class="overflow-x-auto">
        <table class="tbl">
            <thead><tr><th>Layanan</th><th class="tc">Menunggu</th><th class="tc">Disetujui</th><th class="tc">Ditolak</th><th class="tc">Selesai</th></tr></thead>
            <tbody>
            @foreach($rekap as $nama => $r)
                <tr><td class="font-medium">{{ $nama }}</td>
                    <td class="tc">{{ $r['pending'] }}</td><td class="tc">{{ $r['approved'] }}</td>
                    <td class="tc">{{ $r['rejected'] }}</td><td class="tc">{{ $r['selesai'] }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
