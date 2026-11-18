@extends('layouts.app')
@section('title', 'Repositori Dokumen')
@section('subtitle', 'Tempat menyimpan dokumen digital dan arsip fisik. Pilih jenis dokumen di bawah atau gunakan kolom pencarian di atas.')
@section('actions')
    @if(auth()->user()->isStaff())<a href="{{ route('documents.create') }}" class="btn btn-primary"><x-icon name="plus" class="h-4 w-4" /> Tambah dokumen</a>@endif
@endsection
@section('content')
<div x-data="{ view: (window.mdStore && mdStore.get('mdview')) || 'grid' }" x-init="$watch('view', v => mdStore.set('mdview', v))" class="space-y-4">

    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('documents.index', array_filter(['q' => request('q')])) }}" class="chip {{ request('jenis') ? '' : 'chip-on' }}">Semua</a>
        @foreach($jenisList as $j)
            <a href="{{ route('documents.index', array_filter(['jenis' => $j->id, 'q' => request('q')])) }}" class="chip {{ request('jenis') == $j->id ? 'chip-on' : '' }}"><x-icon name="folder" class="h-4 w-4" />{{ $j->nama }}</a>
        @endforeach

        <div class="ml-auto flex overflow-hidden rounded-lg border border-line2" role="group" aria-label="Pilih tampilan">
            <button type="button" @click="view='grid'" :class="view==='grid' ? 'bg-brand-soft text-brand-text' : 'bg-card text-mute hover:bg-soft'" class="grid h-9 w-10 place-items-center" aria-label="Tampilan kotak" title="Tampilan kotak"><x-icon name="grid" class="h-4 w-4" /></button>
            <button type="button" @click="view='list'" :class="view==='list' ? 'bg-brand-soft text-brand-text' : 'bg-card text-mute hover:bg-soft'" class="grid h-9 w-10 place-items-center border-l border-line2" aria-label="Tampilan daftar" title="Tampilan daftar"><x-icon name="list" class="h-4 w-4" /></button>
        </div>
    </div>

    @if(request('q'))<div class="text-sm text-mute">Hasil untuk &ldquo;{{ request('q') }}&rdquo; &middot; <a href="{{ route('documents.index') }}" class="font-medium text-brand-text hover:underline">hapus pencarian</a></div>@endif

    {{-- Tampilan kotak --}}
    <div x-show="view==='grid'" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($docs as $d)
            @php
                $ext = $d->file_path ? strtolower(pathinfo($d->file_path, PATHINFO_EXTENSION)) : null;
                [$warnaFile, $labelFile] = match (true) {
                    $ext === 'pdf' => ['text-bad-text bg-bad-soft', 'PDF'],
                    in_array($ext, ['jpg', 'jpeg', 'png']) => ['text-ok-text bg-ok-soft', strtoupper($ext)],
                    default => ['text-mute bg-soft', 'BELUM ADA FILE'],
                };
            @endphp
            <a href="{{ route('documents.show', $d) }}" class="card group block p-3 transition hover:border-brand/50">
                <div class="mb-2 flex items-center gap-2">
                    <span class="grid h-6 w-6 shrink-0 place-items-center rounded {{ $warnaFile }}"><x-icon name="file" class="h-3.5 w-3.5" /></span>
                    <span class="truncate text-sm font-medium">{{ $d->judul }}</span>
                </div>
                <div class="flex h-32 flex-col items-center justify-center gap-1 rounded-lg bg-soft">
                    <x-icon name="file" class="h-10 w-10 {{ explode(' ', $warnaFile)[0] }}" />
                    <span class="text-[10px] font-medium tracking-wide {{ explode(' ', $warnaFile)[0] }}">{{ $labelFile }}</span>
                </div>
                <div class="mt-2 flex items-center justify-between gap-2 text-xs text-mute">
                    <span class="truncate">{{ auth()->user()->isStaff() ? $d->user->name.' · ' : '' }}{{ $d->jenis->nama }} {{ $d->tahun_ajaran }}</span>
                    @if($d->ada_fisik)<x-badge :status="$d->status_fisik" />@endif
                </div>
            </a>
        @empty
            <div class="card col-span-full">
                <x-empty icon="folder" title="Belum ada dokumen">
                    @if(auth()->user()->isStaff())
                        <p>Mulai dengan menambahkan dokumen pertama.</p>
                        <a href="{{ route('documents.create') }}" class="btn btn-primary">Tambah dokumen</a>
                    @else
                        <p>Dokumen Anda akan muncul di sini setelah petugas mengunggahnya.</p>
                    @endif
                </x-empty>
            </div>
        @endforelse
    </div>

    {{-- Tampilan daftar --}}
    <div x-show="view==='list'" x-cloak class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="tbl">
                <thead><tr>
                    <th>Nama</th>
                    @if(auth()->user()->isStaff())<th>Pemilik</th>@endif
                    <th>Jenis</th><th class="tc">Tahun</th>
                    <th class="tc">File digital</th><th class="tc">Arsip fisik</th></tr></thead>
                <tbody>
                @forelse($docs as $d)
                    <tr>
                        <td class="font-medium"><a href="{{ route('documents.show', $d) }}" class="hover:underline">{{ $d->judul }}</a></td>
                        @if(auth()->user()->isStaff())<td>{{ $d->user->name }}<div class="text-xs text-mute">{{ $d->user->nis }}</div></td>@endif
                        <td>{{ $d->jenis->nama }}</td>
                        <td class="tc">{{ $d->tahun_ajaran ?? '-' }}</td>
                        <td class="tc">{{ $d->file_path ? 'Ada' : '-' }}</td>
                        <td class="tc">@if($d->ada_fisik)<x-badge :status="$d->status_fisik" />@else - @endif</td>
                    </tr>
                @empty
                    <tr><td colspan="6"><x-empty icon="folder" title="Belum ada dokumen">Dokumen yang sudah diunggah akan tampil di daftar ini.</x-empty></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $docs->links() }}
</div>
@endsection
