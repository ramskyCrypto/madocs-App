@extends('layouts.app')
@section('title', $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('subtitle', 'Kolom bertanda * wajib diisi. NIS, kelas, dan jurusan hanya perlu untuk akun siswa.')
@section('content')
<form method="POST" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}" class="card grid max-w-4xl gap-4 p-5 md:grid-cols-2">
    @csrf @if($user->exists) @method('PUT') @endif
    <x-input name="name" label="Nama lengkap" :value="$user->name" :required="true" />
    <x-input name="email" label="Email" type="email" :value="$user->email" :required="true" hint="Dipakai untuk masuk ke MaDocs." />
    <x-field name="role" label="Peran" :required="true" hint="Admin: semua akses. Petugas: memproses pengajuan. Siswa: mengajukan layanan.">
        <select id="role" name="role" class="input">
            @foreach(['admin' => 'Admin', 'petugas' => 'Petugas', 'siswa' => 'Siswa'] as $k => $v)
                <option value="{{ $k }}" @selected(old('role', $user->role) === $k)>{{ $v }}</option>
            @endforeach
        </select>
    </x-field>
    <x-input name="password" :label="'Password'.($user->exists ? ' (kosongkan jika tidak diubah)' : '')" type="password" :required="! $user->exists" hint="Minimal 6 karakter." />
    <x-input name="nis" label="NIS (wajib untuk siswa)" :value="$user->nis" />
    <x-input name="kelas" label="Kelas" :value="$user->kelas" placeholder="XII RPL 1" />
    <x-input name="jurusan" label="Jurusan" :value="$user->jurusan" />
    <x-input name="tahun_lulus" label="Tahun lulus" :value="$user->tahun_lulus" maxlength="4" placeholder="2026" />
    <x-input name="phone" label="No. HP" :value="$user->phone" />
    <x-field name="address" label="Alamat">
        <textarea id="address" name="address" rows="2" class="input">{{ old('address', $user->address) }}</textarea>
    </x-field>
    <label class="flex items-start gap-2.5 rounded-xl bg-soft p-3.5 text-sm md:col-span-2">
        <input type="checkbox" name="is_active" value="1" class="mt-0.5 h-4 w-4 rounded border-line2 text-brand" @checked(old('is_active', $user->is_active ?? true))>
        <span><b class="font-medium">Akun aktif</b><br><span class="text-mute">Hilangkan centang untuk menonaktifkan akun tanpa menghapus datanya.</span></span>
    </label>
    <div class="flex flex-wrap gap-2 md:col-span-2">
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</form>
@endsection
