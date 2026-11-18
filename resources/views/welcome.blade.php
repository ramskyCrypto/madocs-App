<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MaDocs</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak]{display:none !important}
        .fade-in {animation: fadeIn .4s ease forwards}
        @keyframes fadeIn {from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
    </style>
</head>
<body class="bg-bg text-ink min-h-screen flex items-center justify-center px-4 py-10">
<div class="w-full max-w-4xl">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-sm text-muted hover:text-ink transition mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31-2.37-2.37z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        MaDocs
    </a>

    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary/5 via-surface to-surface border border-border shadow-xl shadow-black/5 p-8 sm:p-12">

        {{-- Decorative dots --}}
        <div class="absolute inset-0 opacity-[0.03]">
            <div class="absolute top-0 left-0 w-64 h-64 rounded-full bg-primary/30 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-primary/20 blur-3xl"></div>
        </div>

        <div class="relative">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/5 border border-primary/10 text-primary text-xs font-semibold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Sistem Informasi Akademik
            </span>

            <h1 class="text-3xl sm:text-4xl font-bold text-ink tracking-tight mt-4">
                Selamat Datang di <span class="text-primary">MaDocs</span>
            </h1>
            <p class="text-sm text-muted mt-3 max-w-lg leading-relaxed">
                Semua dokumen akademik, administrasi kelulusan, dan layanan sekolah dalam satu platform. Mulai dari peminjaman rapor, legalisir ijazah, hingga pengambilan dokumen.
            </p>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-8">
                <div class="rounded-xl p-4 bg-white border border-border shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center"><svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m16 0l-8 4"/></svg></div>
                    <div class="text-xs text-muted mt-2">Repositori Dokumen</div>
                </div>
                <div class="rounded-xl p-4 bg-white border border-border shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center"><svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg></div>
                    <div class="text-xs text-muted mt-2">Peminjaman Rapor</div>
                </div>
                <div class="rounded-xl p-4 bg-white border border-border shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center"><svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <div class="text-xs text-muted mt-2">Legalisir</div>
                </div>
                <div class="rounded-xl p-4 bg-white border border-border shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center"><svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/></svg></div>
                    <div class="text-xs text-muted mt-2">Ijazah</div>
                </div>
            </div>
        </div>
    </div>

    <p class="text-center text-xs text-muted mt-6">
        Versi {{ app()->version() }}
    </p>
</div>
</body>
</html>
