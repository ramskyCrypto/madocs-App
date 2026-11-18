@extends('layouts.app')
@section('title', $document->exists ? 'Edit Dokumen' : 'Tambah Dokumen')
@section('subtitle', 'Kolom bertanda * wajib diisi. Berkas bisa diunggah sekarang atau menyusul.')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $document->exists ? route('documents.update', $document) : route('documents.store') }}"
      class="card grid max-w-4xl gap-4 p-5 md:grid-cols-2">
    @csrf @if($document->exists) @method('PUT') @endif
    <x-field name="user_id" label="Pemilik (siswa)" :required="true" hint="Siswa yang memiliki dokumen ini.">
        <select id="user_id" name="user_id" class="input">
            <option value="">-- pilih siswa --</option>
            @foreach($siswa as $s)<option value="{{ $s->id }}" @selected(old('user_id', $document->user_id) == $s->id)>{{ $s->nis }} - {{ $s->name }}</option>@endforeach
        </select>
    </x-field>
    <x-field name="jenis_dokumen_id" label="Jenis dokumen" :required="true">
        <select id="jenis_dokumen_id" name="jenis_dokumen_id" class="input">
            @foreach($jenisList as $j)<option value="{{ $j->id }}" @selected(old('jenis_dokumen_id', $document->jenis_dokumen_id) == $j->id)>{{ $j->nama }}</option>@endforeach
        </select>
    </x-field>
    <x-input name="judul" label="Judul dokumen" :value="$document->judul" :required="true" placeholder="Mis. Buku Rapor Kelas XII" />
    <x-input name="tahun_ajaran" label="Tahun ajaran" :value="$document->tahun_ajaran" placeholder="2025/2026" />
    <div class="md:col-span-2">
        <x-input name="file" label="File digital (PDF/JPG/PNG, maks. 5 MB)" type="file" />
        @if($document->file_path)<p class="hint">File saat ini sudah ada. Unggah file baru hanya jika ingin menggantinya.</p>@endif
    </div>
    <label class="flex items-start gap-2.5 rounded-xl bg-soft p-3.5 text-sm md:col-span-2">
        <input type="checkbox" name="ada_fisik" value="1" class="mt-0.5 h-4 w-4 rounded border-line2 text-brand" @checked(old('ada_fisik', $document->ada_fisik))>
        <span><b class="font-medium">Tersedia arsip fisik di sekolah</b><br><span class="text-mute">Centang jika dokumen aslinya disimpan di sekolah, agar siswa dapat meminjamnya.</span></span>
    </label>
    <x-input name="lokasi_arsip" label="Lokasi arsip fisik" :value="$document->lokasi_arsip" placeholder="Lemari B / Rak 2" hint="Diisi bila arsip fisik tersedia." />
    <x-field name="keterangan" label="Keterangan">
        <textarea id="keterangan" name="keterangan" rows="2" class="input">{{ old('keterangan', $document->keterangan) }}</textarea>
    </x-field>
    <div class="flex flex-wrap gap-2 md:col-span-2">
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('documents.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
@endsection
