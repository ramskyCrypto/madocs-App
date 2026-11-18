@extends('layouts.app')
@section('title', 'Kotak Masuk')
@section('subtitle', 'Daftar pengajuan yang perlu Anda urus. Yang paling mendesak dan paling lama menunggu ada di atas. Klik satu baris untuk memprosesnya.')
@section('content')
@php $tab = ['peminjaman' => 'Peminjaman Rapor', 'legalisir' => 'Legalisir', 'ijazah' => 'Pengambilan Ijazah']; @endphp

<div class="flex flex-wrap gap-2">
    <a href="{{ route('inbox.index') }}" class="chip {{ $jenis ? '' : 'chip-on' }}">Semua <b>{{ $total }}</b></a>
    @foreach($tab as $k => $v)
        <a href="{{ route('inbox.index', ['jenis' => $k]) }}" class="chip {{ $jenis === $k ? 'chip-on' : '' }}">{{ $v }} <b>{{ $hitung[$k] ?? 0 }}</b></a>
    @endforeach
</div>

<div class="card divide-y divide-line overflow-hidden">
    @forelse($items as $it)
        @include('partials.inbox-row', ['it' => $it])
    @empty
        <x-empty icon="check" title="Kotak masuk kosong">Semua pengajuan sudah diurus. Pengajuan baru dari siswa akan muncul di sini.</x-empty>
    @endforelse
</div>
@endsection
