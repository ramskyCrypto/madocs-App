@extends('layouts.app')
@section('title', 'Master Jenis Dokumen')
@section('subtitle', 'Daftar kategori dokumen (mis. Rapor, Ijazah). Kategori ini muncul sebagai pilihan saat menambah dokumen.')
@section('content')
<form method="POST" action="{{ route('jenis-dokumen.store') }}" class="card grid items-start gap-3 p-4 md:grid-cols-4">
    @csrf
    <x-input name="kode" label="Kode" :required="true" placeholder="RPR" />
    <x-input name="nama" label="Nama jenis dokumen" :required="true" placeholder="Rapor" />
    <x-input name="keterangan" label="Keterangan" />
    <div class="md:pt-[1.625rem]"><button class="btn btn-primary btn-block"><x-icon name="plus" class="h-4 w-4" /> Tambah</button></div>
</form>

@foreach($items as $j)
    <form method="POST" action="{{ route('jenis-dokumen.update', $j) }}" id="f{{ $j->id }}">@csrf @method('PUT')</form>
@endforeach

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="tbl">
            <thead><tr>
                <th>Kode</th><th>Nama</th><th>Keterangan</th><th class="tc">Jumlah dokumen</th><th class="tc">Aksi</th></tr></thead>
            <tbody>
            @foreach($items as $j)
                <tr>
                    <td><input form="f{{ $j->id }}" name="kode" value="{{ $j->kode }}" class="input w-24" aria-label="Kode"></td>
                    <td><input form="f{{ $j->id }}" name="nama" value="{{ $j->nama }}" class="input min-w-[10rem]" aria-label="Nama"></td>
                    <td><input form="f{{ $j->id }}" name="keterangan" value="{{ $j->keterangan }}" class="input min-w-[10rem]" aria-label="Keterangan"></td>
                    <td class="tc">{{ $j->documents_count }}</td>
                    <td class="tc whitespace-nowrap">
                        <div class="inline-flex items-center gap-2">
                            <button form="f{{ $j->id }}" class="btn btn-secondary btn-sm">Simpan</button>
                            <form method="POST" action="{{ route('jenis-dokumen.destroy', $j) }}" onsubmit="return confirm('Hapus jenis dokumen ini?')">
                                @csrf @method('DELETE') <button class="btn btn-danger btn-sm">Hapus</button></form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
