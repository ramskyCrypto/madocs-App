@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi')
@section('subtitle', 'Pilih jenis laporan dan rentang tanggal, tekan Tampilkan, lalu cetak atau unduh hasilnya.')
@section('content')
<form method="GET" class="card grid items-end gap-3 p-4 print:hidden sm:grid-cols-2 lg:grid-cols-5">
    <x-field name="jenis" label="Jenis laporan">
        <select id="jenis" name="jenis" class="input">
            @foreach($labels as $k => $v)<option value="{{ $k }}" @selected($jenis === $k)>{{ $v }}</option>@endforeach
        </select>
    </x-field>
    <x-field name="status" label="Status">
        <select id="status" name="status" class="input">
            <option value="">Semua</option>
            @foreach(['pending','approved','rejected','dikembalikan','selesai','diserahkan'] as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ \App\Support\Label::status($s) }}</option>@endforeach
        </select>
    </x-field>
    <x-field name="dari" label="Dari tanggal"><input type="date" id="dari" name="dari" value="{{ request('dari') }}" class="input"></x-field>
    <x-field name="sampai" label="Sampai tanggal"><input type="date" id="sampai" name="sampai" value="{{ request('sampai') }}" class="input"></x-field>
    <button class="btn btn-primary">Tampilkan</button>
</form>

<div class="card overflow-hidden">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line px-4 py-3">
        <div>
            <h2 class="font-medium">{{ $judul }}</h2>
            <div class="text-xs text-mute">Total {{ $total }} data
                @foreach($ringkas as $st => $n) &middot; {{ \App\Support\Label::status($st) }}: {{ $n }} @endforeach</div>
        </div>
        <div class="flex flex-wrap gap-2 print:hidden">
            <a href="{{ route('laporan.pdf', $filter) }}" class="btn btn-secondary btn-sm"><x-icon name="download" class="h-4 w-4" /> Unduh PDF</a>
            <a href="{{ route('laporan.csv', $filter) }}" class="btn btn-secondary btn-sm"><x-icon name="download" class="h-4 w-4" /> Unduh Excel (CSV)</a>
            <button type="button" onclick="window.print()" class="btn btn-secondary btn-sm">Cetak</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <p class="px-4 pt-2 text-xs text-mute sm:hidden print:hidden">Geser tabel ke kanan untuk melihat kolom lainnya.</p>
        <table class="tbl">
            <thead><tr>
                <th>#</th>@foreach($heads as $h)<th>{{ $h }}</th>@endforeach</tr></thead>
            <tbody>
            @forelse($rows as $n => $row)
                <tr><td>{{ $n + 1 }}</td>@foreach($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>
            @empty
                <tr><td colspan="{{ count($heads) + 1 }}"><x-empty icon="chart" title="Tidak ada data">Coba ubah filter atau rentang tanggal.</x-empty></td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
