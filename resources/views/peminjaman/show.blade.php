@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('subtitle', 'Pantau tahapan pengajuan di bawah. Kotak "Apa selanjutnya?" menjelaskan langkah yang perlu dilakukan.')
@section('content')
@php
    $staff = auth()->user()->isStaff();
    $next = $staff
        ? ['pending' => 'Periksa data pengajuan, lalu simpan keputusan pada kotak Verifikasi.', 'approved' => 'Setelah siswa mengembalikan dokumen, tekan "Dokumen Telah Dikembalikan".', 'rejected' => 'Pengajuan ini sudah ditolak. Tidak ada tindakan lagi.', 'dikembalikan' => 'Dokumen sudah dikembalikan. Tidak ada tindakan lagi.']
        : ['pending' => 'Pengajuan Anda sedang menunggu diperiksa petugas. Keputusannya akan tampil di halaman ini. Selama belum diproses, Anda masih bisa membatalkannya.', 'approved' => 'Pengajuan disetujui. Ambil dokumen di ruang Tata Usaha dan kembalikan paling lambat '.$p->tgl_kembali_rencana->translatedFormat('d F Y').'.', 'rejected' => 'Pengajuan ditolak. Baca catatan petugas di bawah, lalu buat pengajuan baru bila perlu.', 'dikembalikan' => 'Dokumen sudah dikembalikan. Tidak ada tindakan lagi. Terima kasih!'];
@endphp
<div class="grid gap-4 lg:grid-cols-3">
    <div class="card space-y-5 p-5 lg:col-span-2">
        <div class="flex items-start justify-between gap-3"><h2 class="font-mono text-base font-semibold">{{ $p->kode }}</h2><x-badge :status="$p->status" /></div>
        <x-stepper flow="peminjaman" :status="$p->status" />
        <x-callout :type="$p->status === 'rejected' ? 'warn' : 'info'" title="Apa selanjutnya?">{{ $next[$p->status] ?? '' }}</x-callout>
        <dl class="grid grid-cols-1 gap-y-1 sm:grid-cols-[9rem_1fr] sm:gap-x-4 sm:gap-y-3">
            <x-info label="Siswa">{{ $p->user->name }} ({{ $p->user->nis }}, {{ $p->user->kelas }})</x-info>
            <x-info label="Dokumen">{{ $p->document->judul }} &middot; {{ $p->document->lokasi_arsip }}</x-info>
            <x-info label="Tanggal pinjam">{{ $p->tgl_pinjam->translatedFormat('d F Y') }}</x-info>
            <x-info label="Rencana kembali">{{ $p->tgl_kembali_rencana->translatedFormat('d F Y') }} @if($p->terlambat)<b class="text-bad-text">(terlambat)</b>@endif</x-info>
            <x-info label="Dikembalikan">{{ $p->tgl_dikembalikan?->translatedFormat('d F Y') ?? '-' }}</x-info>
            <x-info label="Tujuan">{{ $p->tujuan }}</x-info>
            <x-info label="Catatan petugas">{{ $p->catatan_admin ?? '-' }}</x-info>
            <x-info label="Diverifikasi oleh">{{ $p->verifier->name ?? '-' }}</x-info>
        </dl>
        <div class="flex flex-wrap gap-2 border-t border-line pt-4">
            <a href="{{ route('peminjaman.index') }}" class="btn btn-ghost">Kembali</a>
            @if(! $staff && $p->status === 'pending')
                <form method="POST" action="{{ route('peminjaman.destroy', $p) }}" onsubmit="return confirm('Batalkan pengajuan ini?')">@csrf @method('DELETE')
                    <button class="btn btn-danger">Batalkan pengajuan</button></form>
            @endif
        </div>
    </div>

    @if($staff && $p->status !== 'dikembalikan')
    <div class="space-y-4">
        <div class="card p-5">
            <h3 class="mb-3 font-medium">Verifikasi</h3>
            @include('partials.status-form', ['action' => route('peminjaman.status', $p), 'item' => $p])
        </div>
        @if($p->status === 'approved')
        <form method="POST" action="{{ route('peminjaman.kembali', $p) }}" class="card space-y-3 p-5">
            @csrf @method('PATCH')
            <h3 class="font-medium">Catat pengembalian</h3>
            <textarea name="catatan_admin" rows="2" class="input" aria-label="Kondisi dokumen" placeholder="Kondisi dokumen saat dikembalikan">{{ $p->catatan_admin }}</textarea>
            <button class="btn btn-ok btn-block" onclick="return confirm('Catat dokumen sudah dikembalikan?')">Dokumen Telah Dikembalikan</button>
        </form>
        @endif
    </div>
    @endif
</div>
@endsection
