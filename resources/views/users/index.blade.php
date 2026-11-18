@extends('layouts.app')
@section('title', 'Data Pengguna')
@section('subtitle', 'Kelola akun admin, petugas, dan siswa. Hanya admin yang dapat membuka halaman ini.')
@section('actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary"><x-icon name="plus" class="h-4 w-4" /> Tambah Pengguna</a>
@endsection
@section('content')
<form method="GET" class="flex flex-wrap items-center gap-2">
    <div class="relative w-full sm:w-64">
        <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-mute" />
        <input name="q" value="{{ request('q') }}" placeholder="Cari nama / email / NIS" class="input input-icon" aria-label="Cari pengguna">
    </div>
    <select name="role" class="input w-full sm:w-auto" aria-label="Saring berdasarkan peran">
        <option value="">Semua peran</option>
        @foreach(['admin','petugas','siswa'] as $r)<option value="{{ $r }}" @selected(request('role')===$r)>{{ ucfirst($r) }}</option>@endforeach
    </select>
    <button class="btn btn-secondary">Terapkan filter</button>
</form>
<div class="card overflow-hidden">
    <table class="tbl tbl-stack">
        <thead><tr>
            <th>Nama</th><th>Email</th><th class="tc">Peran</th>
            <th>NIS / Kelas</th><th class="tc">Aktif</th><th class="tc">Aksi</th></tr></thead>
        <tbody>
        @foreach($users as $x)
            <tr>
                <td data-label="Nama" class="font-medium">{{ $x->name }}</td><td data-label="Email">{{ $x->email }}</td>
                <td data-label="Peran" class="tc"><span class="rounded-full bg-brand-soft px-2.5 py-0.5 text-xs font-medium text-brand-text">{{ ucfirst($x->role) }}</span></td>
                <td data-label="NIS / Kelas">{{ $x->nis ? $x->nis.' / '.$x->kelas : '-' }}</td>
                <td data-label="Aktif" class="tc">{{ $x->is_active ? 'Ya' : 'Tidak' }}</td>
                <td data-label="" class="tc aksi whitespace-nowrap">
                    <div class="inline-flex items-center gap-2">
                        <a href="{{ route('users.edit', $x) }}" class="btn btn-secondary btn-sm">Edit</a>
                        @if($x->id !== auth()->id())
                        <form method="POST" action="{{ route('users.destroy', $x) }}" onsubmit="return confirm('Hapus pengguna ini beserta seluruh datanya? Tindakan tidak dapat dibatalkan.')">
                            @csrf @method('DELETE') <button class="btn btn-danger btn-sm">Hapus</button></form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{ $users->links() }}
@endsection
