@extends('layouts.app')
@section('title', 'Jadwalkan Pengambilan Ijazah')
@section('subtitle', 'Pilih hari dan jam pengambilan. Pengambilan hanya dilayani pada hari kerja.')
@section('content')
<form method="POST" action="{{ route('ijazah.store') }}" class="card max-w-2xl space-y-4 p-5" x-data="{ wakil: {{ old('diwakilkan') ? 'true' : 'false' }} }">
    @csrf
    <x-callout type="tip" title="Yang perlu Anda ketahui">Setelah disetujui petugas, Anda akan mendapat <b>kode validasi</b>. Kode ini wajib ditunjukkan saat pengambilan, beserta KTP atau kartu pelajar.</x-callout>
    <div class="grid gap-4 sm:grid-cols-2">
        <x-input name="jadwal_tanggal" label="Tanggal pengambilan" type="date" :min="now()->toDateString()" :required="true" hint="Pilih hari kerja (Senin sampai Jumat)." />
        <x-field name="jadwal_jam" label="Jam" :required="true" hint="Jam yang penuh tidak bisa dipilih lagi.">
            <select id="jadwal_jam" name="jadwal_jam" class="input">
                @foreach($jamList as $j)<option value="{{ $j }}" @selected(old('jadwal_jam') === $j)>{{ $j }}</option>@endforeach
            </select>
        </x-field>
    </div>
    <label class="flex items-start gap-2.5 rounded-xl bg-soft p-3.5 text-sm">
        <input type="checkbox" name="diwakilkan" value="1" x-model="wakil" class="mt-0.5 h-4 w-4 rounded border-line2 text-brand">
        <span><b class="font-medium">Diwakilkan orang lain</b><br><span class="text-mute">Centang jika yang mengambil bukan Anda sendiri (mis. orang tua atau wali).</span></span>
    </label>
    <div x-show="wakil" x-cloak class="grid gap-4 rounded-xl border border-line p-4 sm:grid-cols-3">
        <x-input name="nama_pengambil" label="Nama pengambil" />
        <x-input name="hubungan" label="Hubungan" placeholder="Ayah / Ibu / Wali" />
        <x-input name="no_identitas" label="No. KTP pengambil" hint="Harus sama saat diambil." />
    </div>
    <x-field name="catatan" label="Catatan">
        <textarea id="catatan" name="catatan" rows="2" class="input" placeholder="Opsional">{{ old('catatan') }}</textarea>
    </x-field>
    <div class="flex flex-wrap gap-2">
        <button class="btn btn-primary">Kirim Pengajuan</button>
        <a href="{{ route('ijazah.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
@endsection
