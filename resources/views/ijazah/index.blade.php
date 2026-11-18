@extends('layouts.app')
@section('title', 'Pengambilan Ijazah Asli')
@section('subtitle', 'Jadwalkan pengambilan ijazah asli. Setelah disetujui, Anda mendapat kode validasi yang ditunjukkan kepada petugas.')
@section('actions')
    @if(auth()->user()->isSiswa())<a href="{{ route('ijazah.create') }}" class="btn btn-primary"><x-icon name="plus" class="h-4 w-4" /> Jadwalkan Pengambilan</a>@endif
@endsection
@section('content')
@include('partials.filter', ['search' => auth()->user()->isStaff(), 'statuses' => ['pending','approved','rejected','diserahkan']])
<div class="card overflow-hidden">
    <table class="tbl tbl-stack">
        <thead><tr>
            <th>Kode</th>
            @if(auth()->user()->isStaff())<th>Siswa</th>@endif
            <th class="tc">Jadwal</th><th>Pengambil</th><th class="tc">Status</th><th></th></tr></thead>
        <tbody>
        @forelse($items as $i)
            <tr>
                <td data-label="Kode" class="font-mono text-xs">{{ $i->kode }}</td>
                @if(auth()->user()->isStaff())<td data-label="Siswa">{{ $i->user->name }}</td>@endif
                <td data-label="Jadwal" class="tc"><span>{{ $i->jadwal_tanggal->format('d/m/Y') }}<span class="block text-xs text-mute">{{ $i->jadwal_jam }}</span></span></td>
                <td data-label="Pengambil">{{ $i->diwakilkan ? $i->nama_pengambil.' ('.$i->hubungan.')' : 'Siswa sendiri' }}</td>
                <td data-label="Status" class="tc"><x-badge :status="$i->status" /></td>
                <td data-label="" class="aksi"><a href="{{ route('ijazah.show', $i) }}" class="btn btn-secondary btn-sm">Lihat detail</a></td>
            </tr>
        @empty
            <tr><td colspan="6"><x-empty icon="cap" title="Belum ada jadwal pengambilan">
                @if(auth()->user()->isSiswa())<p>Sudah waktunya mengambil ijazah asli? Pilih tanggal dan jam yang sesuai.</p><a href="{{ route('ijazah.create') }}" class="btn btn-primary">Jadwalkan Pengambilan</a>@else<p>Jadwal dari siswa akan tampil di sini.</p>@endif
            </x-empty></td></tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $items->links() }}
@endsection
