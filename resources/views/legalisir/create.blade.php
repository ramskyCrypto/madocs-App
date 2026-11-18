@extends('layouts.app')
@section('title', 'Ajukan Legalisir')
@section('subtitle', 'Pilih dokumen yang akan dilegalisir dan jumlah rangkapnya. Petugas akan memeriksa pengajuan Anda.')
@section('content')
<form method="POST" action="{{ route('legalisir.store') }}" class="card max-w-2xl space-y-4 p-5">
    @csrf
    <x-callout type="tip" title="Cara mengisi">Pilih Ijazah atau Rapor, tentukan jumlah rangkap, lalu tulis untuk apa dokumen ini dipakai (mis. melamar kerja).</x-callout>
    <div class="grid gap-4 sm:grid-cols-2">
        <x-field name="jenis" label="Jenis dokumen" :required="true">
            <select id="jenis" name="jenis" class="input">
                <option value="ijazah" @selected(old('jenis') === 'ijazah')>Ijazah</option>
                <option value="rapor" @selected(old('jenis') === 'rapor')>Rapor</option>
            </select>
        </x-field>
        <x-input name="jumlah_rangkap" label="Jumlah rangkap (1-10)" type="number" :value="1" min="1" max="10" :required="true" hint="Berapa salinan legalisir yang dibutuhkan." />
    </div>
    <x-field name="keperluan" label="Keperluan" :required="true">
        <textarea id="keperluan" name="keperluan" rows="3" class="input" placeholder="Mis. melamar kerja / daftar kuliah">{{ old('keperluan') }}</textarea>
    </x-field>
    <div class="flex flex-wrap gap-2">
        <button class="btn btn-primary">Kirim Pengajuan</button>
        <a href="{{ route('legalisir.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
@endsection
