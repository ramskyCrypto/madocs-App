@extends('layouts.app')
@section('title', 'Pengajuan Legalisir')
@section('subtitle', 'Minta salinan ijazah atau rapor yang dilegalisir sekolah. Ajukan, tunggu persetujuan, lalu ambil di ruang Tata Usaha.')
@section('actions')
    @if(auth()->user()->isSiswa())<a href="{{ route('legalisir.create') }}" class="btn btn-primary"><x-icon name="plus" class="h-4 w-4" /> Ajukan Legalisir</a>@endif
@endsection
@section('content')
@include('partials.filter', ['search' => auth()->user()->isStaff(), 'statuses' => ['pending','approved','rejected','selesai']])
<div class="card overflow-hidden">
    <table class="tbl tbl-stack">
        <thead><tr>
            <th>Kode</th>
            @if(auth()->user()->isStaff())<th>Siswa</th>@endif
            <th class="tc">Dokumen</th><th class="tc">Rangkap</th><th>Keperluan</th><th class="tc">Status</th><th></th></tr></thead>
        <tbody>
        @forelse($items as $l)
            <tr>
                <td data-label="Kode" class="font-mono text-xs">{{ $l->kode }}</td>
                @if(auth()->user()->isStaff())<td data-label="Siswa">{{ $l->user->name }}</td>@endif
                <td data-label="Dokumen" class="tc">{{ ucfirst($l->jenis) }}</td>
                <td data-label="Rangkap" class="tc">{{ $l->jumlah_rangkap }}</td>
                <td data-label="Keperluan">{{ \Illuminate\Support\Str::limit($l->keperluan, 40) }}</td>
                <td data-label="Status" class="tc"><x-badge :status="$l->status" /></td>
                <td data-label="" class="aksi"><a href="{{ route('legalisir.show', $l) }}" class="btn btn-secondary btn-sm">Lihat detail</a></td>
            </tr>
        @empty
            <tr><td colspan="7"><x-empty icon="shield" title="Belum ada pengajuan legalisir">
                @if(auth()->user()->isSiswa())<p>Perlu salinan ijazah atau rapor yang dilegalisir? Ajukan di sini.</p><a href="{{ route('legalisir.create') }}" class="btn btn-primary">Ajukan Legalisir</a>@else<p>Pengajuan dari siswa akan tampil di sini.</p>@endif
            </x-empty></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $items->links() }}
@endsection
