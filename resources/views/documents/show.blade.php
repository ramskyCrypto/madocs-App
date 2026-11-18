@extends('layouts.app')
@section('title', 'Detail Dokumen')
@section('subtitle', 'Informasi lengkap satu dokumen. Gunakan tombol di bawah untuk melihat atau mengunduh berkasnya.')
@section('content')
<div class="card max-w-3xl space-y-5 p-5">
    <div class="flex flex-wrap items-start justify-between gap-2">
        <h2 class="text-lg font-semibold">{{ $document->judul }}</h2>
        @if($document->ada_fisik)<x-badge :status="$document->status_fisik" />@endif
    </div>
    <dl class="grid grid-cols-1 gap-y-1 sm:grid-cols-[9rem_1fr] sm:gap-x-4 sm:gap-y-3">
        <x-info label="Pemilik">{{ $document->user->name }} ({{ $document->user->nis }})</x-info>
        <x-info label="Jenis">{{ $document->jenis->nama }}</x-info>
        <x-info label="Tahun ajaran">{{ $document->tahun_ajaran ?? '-' }}</x-info>
        <x-info label="Arsip fisik">{{ $document->ada_fisik ? ($document->lokasi_arsip ?? 'Ada') : 'Tidak ada' }}</x-info>
        <x-info label="Keterangan">{{ $document->keterangan ?? '-' }}</x-info>
    </dl>
    @if(! $document->file_path)
        <x-callout type="info">File digital belum diunggah. {{ auth()->user()->isStaff() ? 'Tekan Edit untuk mengunggahnya.' : 'Petugas akan mengunggahnya.' }}</x-callout>
    @endif
    <div class="flex flex-wrap gap-2 border-t border-line pt-4">
        @if($document->file_path)
            <a href="{{ route('documents.file', $document) }}" target="_blank" class="btn btn-primary"><x-icon name="eye" class="h-4 w-4" /> Lihat file</a>
            <a href="{{ route('documents.file', [$document, 'unduh' => 1]) }}" class="btn btn-secondary"><x-icon name="download" class="h-4 w-4" /> Unduh</a>
        @endif
        @if(auth()->user()->isStaff())
            <a href="{{ route('documents.edit', $document) }}" class="btn btn-secondary">Edit</a>
            <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('Hapus dokumen ini? Tindakan ini tidak dapat dibatalkan.')">
                @csrf @method('DELETE') <button class="btn btn-danger">Hapus</button></form>
        @endif
        <a href="{{ route('documents.index') }}" class="btn btn-ghost">Kembali</a>
    </div>
</div>
@endsection
