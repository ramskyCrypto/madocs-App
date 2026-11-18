@extends('layouts.app')
@section('title', 'Detail Pengambilan Ijazah')
@section('subtitle', 'Pantau tahapan pengajuan di bawah. Kotak "Apa selanjutnya?" menjelaskan langkah yang perlu dilakukan.')
@section('content')
@php
    $staff = auth()->user()->isStaff();
    $next = $staff
        ? ['pending' => 'Periksa jadwal dan data pengambil, lalu simpan keputusan pada kotak Verifikasi.', 'approved' => 'Saat pengambil datang, minta kode validasi dan identitasnya, isi kolom di kanan, lalu tekan "Validasi & Serahkan".', 'rejected' => 'Pengajuan ini sudah ditolak. Tidak ada tindakan lagi.', 'diserahkan' => 'Ijazah sudah diserahkan. Tidak ada tindakan lagi.']
        : ['pending' => 'Pengajuan Anda sedang menunggu diperiksa petugas. Kode validasi akan muncul di halaman ini setelah disetujui. Selama belum diproses, Anda masih bisa membatalkannya.', 'approved' => 'Disetujui. Datang sesuai jadwal, bawa KTP atau kartu pelajar, dan tunjukkan kode validasi di bawah kepada petugas.', 'rejected' => 'Pengajuan ditolak. Baca catatan petugas di bawah, lalu buat jadwal baru bila perlu.', 'diserahkan' => 'Ijazah sudah diserahkan. Tidak ada tindakan lagi.'];
@endphp
<div class="grid gap-4 lg:grid-cols-3">
    <div class="card space-y-5 p-5 lg:col-span-2">
        <div class="flex items-start justify-between gap-3"><h2 class="font-mono text-base font-semibold">{{ $i->kode }}</h2><x-badge :status="$i->status" /></div>
        <x-stepper flow="ijazah" :status="$i->status" />
        <x-callout :type="$i->status === 'rejected' ? 'warn' : 'info'" title="Apa selanjutnya?">{{ $next[$i->status] ?? '' }}</x-callout>

        @if(! $staff && $i->status === 'approved')
            <div class="rounded-xl border border-brand/30 bg-brand-soft p-4 text-center">
                <div class="text-xs font-medium uppercase tracking-wider text-brand-text">Kode validasi pengambilan</div>
                <div class="my-1 font-mono text-4xl font-semibold tracking-widest text-brand-text">{{ $i->kode_validasi }}</div>
                <div class="text-xs text-mute">Tunjukkan kode ini beserta KTP/kartu pelajar kepada petugas.</div>
            </div>
        @endif

        <dl class="grid grid-cols-1 gap-y-1 sm:grid-cols-[9rem_1fr] sm:gap-x-4 sm:gap-y-3">
            <x-info label="Siswa">{{ $i->user->name }} ({{ $i->user->nis }})</x-info>
            <x-info label="Jadwal">{{ $i->jadwal_tanggal->translatedFormat('l, d F Y') }}, {{ $i->jadwal_jam }}</x-info>
            <x-info label="Pengambil">{{ $i->diwakilkan ? $i->nama_pengambil.' ('.$i->hubungan.'), KTP '.$i->no_identitas : 'Siswa yang bersangkutan' }}</x-info>
            <x-info label="Catatan siswa">{{ $i->catatan ?? '-' }}</x-info>
            <x-info label="Catatan petugas">{{ $i->catatan_admin ?? '-' }}</x-info>
            @if($staff && $i->status === 'approved')<x-info label="Kode validasi"><span class="font-mono">{{ $i->kode_validasi }}</span> <span class="text-xs text-mute">(tanyakan ke pengambil, jangan dibacakan)</span></x-info>@endif
            @if($i->status === 'diserahkan')
                <x-info label="Diserahkan">{{ $i->tgl_diserahkan->translatedFormat('d F Y H:i') }} oleh {{ $i->penyerah->name ?? '-' }}</x-info>
                <x-info label="Penerima">{{ $i->nama_penerima }} (ID {{ $i->no_identitas_penerima }})</x-info>
            @endif
        </dl>
        <div class="flex flex-wrap gap-2 border-t border-line pt-4">
            <a href="{{ route('ijazah.index') }}" class="btn btn-ghost">Kembali</a>
            @if(! $staff && $i->status === 'pending')
                <form method="POST" action="{{ route('ijazah.destroy', $i) }}" onsubmit="return confirm('Batalkan pengajuan ini?')">@csrf @method('DELETE')
                    <button class="btn btn-danger">Batalkan pengajuan</button></form>
            @endif
        </div>
    </div>

    @if($staff && $i->status !== 'diserahkan')
    <div class="space-y-4">
        <div class="card p-5"><h3 class="mb-3 font-medium">Verifikasi pengajuan</h3>
            @include('partials.status-form', ['action' => route('ijazah.status', $i), 'item' => $i])</div>
        @if($i->status === 'approved')
        <form method="POST" action="{{ route('ijazah.serahkan', $i) }}" class="card space-y-3 p-5">
            @csrf @method('PATCH')
            <h3 class="font-medium">Validasi &amp; penyerahan ijazah asli</h3>
            <x-input name="kode_validasi" label="Kode validasi dari pengambil" :required="true" />
            <x-input name="nama_penerima" label="Nama penerima (sesuai identitas)" :required="true" />
            <x-input name="no_identitas_penerima" label="No. identitas penerima (KTP/NIS)" :required="true" />
            <button class="btn btn-ok btn-block" onclick="return confirm('Serahkan ijazah asli? Tindakan tidak dapat dibatalkan.')">Validasi &amp; Serahkan</button>
        </form>
        @endif
    </div>
    @endif
</div>
@endsection
