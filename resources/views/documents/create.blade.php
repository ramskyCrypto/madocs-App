@extends('layouts.app')
@section('title', $document->exists ? 'Edit Dokumen' : 'Tambah Dokumen')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $document->exists ? route('documents.update', $document) : route('documents.store') }}"
      class="bg-white rounded-xl border border-border p-5 grid md:grid-cols-2 gap-4 max-w-4xl shadow-sm">
    @csrf @if($document->exists) @method('PUT') @endif
    <div>
        <label class="block text-sm font-medium text-ink mb-1.5">Pemilik (siswa) <span class="text-rose-500">*</span></label>
        <select name="user_id" class="w-full rounded-xl border border-border bg-slate-50 text-sm text-ink focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition px-3.5 py-2.5 min-h-[44px]">
            <option value="">-- pilih siswa --</option>
            @foreach($siswa as $s)<option value="{{ $s->id }}" @selected(old('user_id', $document->user_id) == $s->id)>{{ $s->nis }} - {{ $s->name }}</option>@endforeach
        </select>
        @error('user_id')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-ink mb-1.5">Jenis dokumen <span class="text-rose-500">*</span></label>
        <select name="jenis_dokumen_id" class="w-full rounded-xl border border-border bg-slate-50 text-sm text-ink focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition px-3.5 py-2.5 min-h-[44px]">
            @foreach($jenisList as $j)<option value="{{ $j->id }}" @selected(old('jenis_dokumen_id', $document->jenis_dokumen_id) == $j->id)>{{ $j->nama }}</option>@endforeach
        </select>
        @error('jenis_dokumen_id')<p class="text-xs text-rose-600 mt-1">{{ $message }}</p>@enderror
    </div>
    <x-input name="judul" label="Judul dokumen" :value="$document->judul" required />
    <x-input name="tahun_ajaran" label="Tahun ajaran" :value="$document->tahun_ajaran" placeholder="2025/2026" />
    <div class="md:col-span-2">
        <x-input name="file" label="File digital (PDF/JPG/PNG, maks. 5 MB)" type="file" />
        @if($document->file_path)<p class="text-xs text-muted mt-1">File saat ini sudah ada. Unggah baru untuk mengganti.</p>@endif
    </div>
    <label class="flex items-center gap-2 text-sm md:col-span-2">
        <input type="checkbox" name="ada_fisik" value="1" class="rounded border-border text-primary focus:ring-primary/30" @checked(old('ada_fisik', $document->ada_fisik))>
        Tersedia arsip fisik di sekolah (dapat dipinjam)
    </label>
    <x-input name="lokasi_arsip" label="Lokasi arsip fisik" :value="$document->lokasi_arsip" placeholder="Lemari B / Rak 2" />
    <div>
        <label class="block text-sm font-medium text-ink mb-1.5">Keterangan</label>
        <textarea name="keterangan" rows="2" class="w-full rounded-xl border border-border bg-slate-50 text-sm px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">{{ old('keterangan', $document->keterangan) }}</textarea>
    </div>
    <div class="md:col-span-2 flex flex-wrap gap-2">
        <button class="px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold shadow-sm hover:bg-primary-hover transition">Simpan</button>
        <a href="{{ route('documents.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 text-ink text-sm hover:bg-slate-200 transition">Batal</a>
    </div>
</form>
@endsection
