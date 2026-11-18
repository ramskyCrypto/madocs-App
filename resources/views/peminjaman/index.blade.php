@extends('layouts.app')
@section('title', 'Peminjaman Buku Rapor Fisik')
@section('subtitle', 'Pinjam buku rapor asli dari arsip sekolah. Ajukan, tunggu persetujuan petugas, lalu ambil dan kembalikan tepat waktu.')
@section('actions')
    @if(auth()->user()->isSiswa())<a href="{{ route('peminjaman.create') }}" class="btn btn-primary"><x-icon name="plus" class="h-4 w-4" /> Ajukan Peminjaman</a>@endif
@endsection
@section('content')
@include('partials.filter', ['search' => auth()->user()->isStaff(), 'statuses' => ['pending','approved','rejected','dikembalikan']])
<div class="card overflow-hidden">
    <table class="tbl tbl-stack">
        <thead><tr>
            <th>Kode</th>
            @if(auth()->user()->isStaff())<th>Siswa</th>@endif
            <th>Dokumen</th><th class="tc">Pinjam</th><th class="tc">Rencana kembali</th><th class="tc">Status</th><th></th></tr></thead>
        <tbody>
        @forelse($items as $p)
            <tr>
                <td data-label="Kode" class="font-mono text-xs">{{ $p->kode }}</td>
                @if(auth()->user()->isStaff())<td data-label="Siswa">{{ $p->user->name }}</td>@endif
                <td data-label="Dokumen">{{ $p->document->judul }}</td>
                <td data-label="Pinjam" class="tc">{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
                <td data-label="Rencana kembali" class="tc"><span>{{ $p->tgl_kembali_rencana->format('d/m/Y') }} @if($p->terlambat)<span class="text-xs font-semibold text-bad-text">(terlambat)</span>@endif</span></td>
                <td data-label="Status" class="tc"><x-badge :status="$p->status" /></td>
                <td data-label="" class="aksi"><a href="{{ route('peminjaman.show', $p) }}" class="btn btn-secondary btn-sm">Lihat detail</a></td>
            </tr>
        @empty
            <tr><td colspan="7"><x-empty icon="book" title="Belum ada peminjaman">
                @if(auth()->user()->isSiswa())<p>Butuh buku rapor asli? Ajukan peminjaman, petugas akan memeriksanya.</p><a href="{{ route('peminjaman.create') }}" class="btn btn-primary">Ajukan Peminjaman</a>@else<p>Pengajuan dari siswa akan tampil di sini.</p>@endif
            </x-empty></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $items->links() }}
@endsection
