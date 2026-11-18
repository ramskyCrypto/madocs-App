@extends('layouts.app')
@section('title', 'Detail Legalisir')
@section('subtitle', 'Pantau tahapan pengajuan di bawah. Kotak "Apa selanjutnya?" menjelaskan langkah yang perlu dilakukan.')
@section('content')
@php
    $staff = auth()->user()->isStaff();
    $next = $staff
        ? ['pending' => 'Periksa data pengajuan, lalu simpan keputusan pada kotak Verifikasi.', 'approved' => 'Siapkan dokumen legalisir. Saat siswa mengambilnya, isi nama penerima lalu tekan "Dokumen Telah Diserahkan".', 'rejected' => 'Pengajuan ini sudah ditolak. Tidak ada tindakan lagi.', 'selesai' => 'Dokumen sudah diserahkan. Tidak ada tindakan lagi.']
        : ['pending' => 'Pengajuan Anda sedang menunggu diperiksa petugas. Keputusannya akan tampil di halaman ini. Selama belum diproses, Anda masih bisa membatalkannya.', 'approved' => 'Pengajuan disetujui. Silakan ambil dokumen legalisir di ruang Tata Usaha.', 'rejected' => 'Pengajuan ditolak. Baca catatan petugas di bawah, lalu buat pengajuan baru bila perlu.', 'selesai' => 'Dokumen sudah Anda terima. Tidak ada tindakan lagi.'];
@endphp
<div class="grid gap-4 lg:grid-cols-3">
    <div class="card space-y-5 p-5 lg:col-span-2">
        <div class="flex items-start justify-between gap-3"><h2 class="font-mono text-base font-semibold">{{ $l->kode }}</h2><x-badge :status="$l->status" /></div>
        <x-stepper flow="legalisir" :status="$l->status" />
        <x-callout :type="$l->status === 'rejected' ? 'warn' : ($l->status === 'approved' && ! $staff ? 'tip' : 'info')" title="Apa selanjutnya?">{{ $next[$l->status] ?? '' }}</x-callout>
        <dl class="grid grid-cols-1 gap-y-1 sm:grid-cols-[9rem_1fr] sm:gap-x-4 sm:gap-y-3">
            <x-info label="Siswa">{{ $l->user->name }} ({{ $l->user->nis }})</x-info>
            <x-info label="Dokumen">{{ ucfirst($l->jenis) }} &times; {{ $l->jumlah_rangkap }} rangkap</x-info>
            <x-info label="Keperluan">{{ $l->keperluan }}</x-info>
            <x-info label="Diajukan">{{ $l->created_at->translatedFormat('d F Y H:i') }}</x-info>
            <x-info label="Catatan petugas">{{ $l->catatan_admin ?? '-' }}</x-info>
            <x-info label="Penyerahan">{{ $l->tgl_selesai ? $l->tgl_selesai->translatedFormat('d F Y H:i').' - diterima '.$l->diterima_oleh : '-' }}</x-info>
            <x-info label="Diverifikasi oleh">{{ $l->verifier->name ?? '-' }}</x-info>
        </dl>
        <div class="flex flex-wrap gap-2 border-t border-line pt-4">
            <a href="{{ route('legalisir.index') }}" class="btn btn-ghost">Kembali</a>
            @if(! $staff && $l->status === 'pending')
                <form method="POST" action="{{ route('legalisir.destroy', $l) }}" onsubmit="return confirm('Batalkan pengajuan ini?')">@csrf @method('DELETE')
                    <button class="btn btn-danger">Batalkan pengajuan</button></form>
            @endif
        </div>
    </div>

    @if($staff && $l->status !== 'selesai')
    <div class="space-y-4">
        <div class="card p-5"><h3 class="mb-3 font-medium">Verifikasi</h3>
            @include('partials.status-form', ['action' => route('legalisir.status', $l), 'item' => $l])</div>
        @if($l->status === 'approved')
        <form method="POST" action="{{ route('legalisir.selesai', $l) }}" class="card space-y-3 p-5">
            @csrf @method('PATCH')
            <h3 class="font-medium">Catat penyerahan</h3>
            <x-input name="diterima_oleh" label="Diterima oleh" :value="$l->user->name" :required="true" hint="Nama orang yang menerima dokumen." />
            <button class="btn btn-ok btn-block">Dokumen Telah Diserahkan</button>
        </form>
        @endif
    </div>
    @endif
</div>
@endsection
