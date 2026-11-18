@extends('layouts.app')
@section('title', 'Pengaturan Profil')
@section('subtitle', 'Perbarui data diri dan password Anda. Perubahan baru berlaku setelah menekan tombol simpan.')
@section('content')
<div class="grid gap-4 lg:grid-cols-2">
    <form method="POST" action="{{ route('profile.update') }}" class="card space-y-4 p-5">
        @csrf @method('PUT')
        <h2 class="font-medium">Data profil</h2>
        <x-input name="name" label="Nama" :value="$user->name" :required="true" />
        <x-input name="email" label="Email" type="email" :value="$user->email" :required="true" />
        @if($user->isSiswa())
            <div class="rounded-xl bg-soft p-3 text-sm text-mute">NIS: <b class="text-ink">{{ $user->nis }}</b> &middot; Kelas: <b class="text-ink">{{ $user->kelas }}</b> &middot; Jurusan: <b class="text-ink">{{ $user->jurusan }}</b></div>
        @endif
        <x-input name="phone" label="No. HP" :value="$user->phone" />
        <x-field name="address" label="Alamat">
            <textarea id="address" name="address" rows="2" class="input">{{ old('address', $user->address) }}</textarea>
        </x-field>
        <button class="btn btn-primary">Simpan profil</button>
    </form>

    <form method="POST" action="{{ route('profile.password') }}" class="card space-y-4 self-start p-5">
        @csrf @method('PUT')
        <h2 class="font-medium">Ganti password</h2>
        <x-input name="current_password" label="Password saat ini" type="password" :required="true" />
        <x-input name="password" label="Password baru (min. 6 karakter)" type="password" :required="true" hint="Gunakan kombinasi huruf dan angka agar sulit ditebak." />
        <x-input name="password_confirmation" label="Ulangi password baru" type="password" :required="true" />
        <button class="btn btn-secondary">Ganti password</button>
    </form>
</div>
@endsection
