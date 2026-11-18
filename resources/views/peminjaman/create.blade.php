@extends('layouts.app')
@section('title', 'Ajukan Peminjaman Rapor Fisik')
@section('subtitle', 'Isi formulir singkat ini. Setelah dikirim, petugas akan memeriksanya dan statusnya bisa Anda pantau di menu Peminjaman Rapor.')
@section('content')
<form method="POST" action="{{ route('peminjaman.store') }}" class="card max-w-2xl space-y-4 p-5">
    @csrf
    <x-callout type="tip" title="Cara mengisi">Pilih dokumen yang ingin dipinjam, tentukan tanggal pinjam dan rencana kembali, lalu jelaskan tujuannya dengan singkat.</x-callout>
    <x-field name="document_id" label="Dokumen fisik" :required="true">
        <select id="document_id" name="document_id" class="input">
            <option value="">-- pilih dokumen --</option>
            @foreach($documents as $d)<option value="{{ $d->id }}" @selected(old('document_id') == $d->id)>{{ $d->judul }} ({{ $d->tahun_ajaran }}) - {{ $d->lokasi_arsip }}</option>@endforeach
        </select>
        @if($documents->isEmpty())<p class="hint text-warn-text">Belum ada dokumen fisik yang tersedia untuk dipinjam.</p>@endif
    </x-field>
    <div class="grid gap-4 sm:grid-cols-2">
        <x-input name="tgl_pinjam" label="Tanggal pinjam" type="date" :value="now()->toDateString()" :min="now()->toDateString()" :required="true" />
        <x-input name="tgl_kembali_rencana" label="Rencana kembali (maks. 14 hari)" type="date" :value="now()->addDays(3)->toDateString()" :required="true" hint="Kembalikan paling lambat tanggal ini." />
    </div>
    <x-field name="tujuan" label="Tujuan peminjaman" :required="true">
        <textarea id="tujuan" name="tujuan" rows="3" class="input" placeholder="Mis. melengkapi berkas pendaftaran kuliah">{{ old('tujuan') }}</textarea>
    </x-field>
    <div class="flex flex-wrap gap-2">
        <button class="btn btn-primary">Kirim Pengajuan</button>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
@endsection
