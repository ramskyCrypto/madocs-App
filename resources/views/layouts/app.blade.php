<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') &middot; MaDocs</title>
    @include('partials.head')
</head>
<body class="min-h-[100dvh] bg-canvas text-ink antialiased" x-data="{ open: false }" @keydown.escape.window="open=false">
@php
    $u = auth()->user();
    $staff = $u->isStaff();
    $inboxCount = $staff ? \App\Support\Inbox::count() : 0;

    $groups = [];
    $utama = [['dashboard', 'Beranda', 'dashboard', 'home']];
    if ($staff) { $utama[] = ['inbox.index', 'Kotak Masuk', 'inbox.*', 'inbox']; }
    $groups['Utama'] = $utama;
    $groups['Dokumen & layanan'] = [
        ['documents.index', 'Repositori Dokumen', 'documents.*', 'folder'],
        ['peminjaman.index', 'Peminjaman Rapor', 'peminjaman.*', 'book'],
        ['legalisir.index', 'Legalisir', 'legalisir.*', 'shield'],
        ['ijazah.index', 'Pengambilan Ijazah', 'ijazah.*', 'cap'],
    ];
    if ($staff) { $groups['Laporan'] = [['laporan.index', 'Laporan', 'laporan.*', 'chart']]; }
    if ($u->isAdmin()) {
        $groups['Master data'] = [['users.index', 'Pengguna', 'users.*', 'users'], ['jenis-dokumen.index', 'Jenis Dokumen', 'jenis-dokumen.*', 'tag']];
    }
    $groups['Bantuan'] = [['help', 'Panduan Penggunaan', 'help', 'help']];

    $baru = $staff
        ? array_filter([['Tambah dokumen', route('documents.create')], $u->isAdmin() ? ['Tambah pengguna', route('users.create')] : null])
        : [['Ajukan peminjaman rapor', route('peminjaman.create')], ['Ajukan legalisir', route('legalisir.create')], ['Jadwalkan ambil ijazah', route('ijazah.create')]];
    $roleLabel = ['admin' => 'Admin', 'petugas' => 'Petugas', 'siswa' => 'Siswa'][$u->role] ?? ucfirst($u->role);
@endphp
<a href="#konten" class="skip">Lewati ke konten</a>

{{-- Sidebar --}}
<div x-show="open" x-cloak @click="open=false" class="fixed inset-0 z-30 bg-black/40 lg:hidden print:hidden"></div>
<aside :class="{ '-translate-x-full': !open }"
       class="-translate-x-full fixed inset-y-0 left-0 z-40 flex w-64 transform flex-col border-r border-line bg-card transition-transform lg:translate-x-0 print:hidden" aria-label="Menu utama">
    <div class="flex h-14 shrink-0 items-center justify-between px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <span class="grid h-8 w-8 place-items-center rounded-lg bg-brand text-white"><x-icon name="file" class="h-4 w-4" /></span>
            <span class="text-[17px] font-semibold tracking-tight">MaDocs</span>
        </a>
        <button type="button" @click="open=false" class="btn btn-ghost btn-sm lg:hidden" aria-label="Tutup menu"><x-icon name="x" class="h-4 w-4" /></button>
    </div>

    <div class="px-3 pb-2" x-data="{ o: false }" @click.outside="o=false" @keydown.escape.window="o=false">
        <div class="relative">
            <button type="button" @click="o=!o" class="btn btn-primary btn-block" :aria-expanded="o.toString()"><x-icon name="plus" class="h-4 w-4" /> {{ $staff ? 'Tambah data' : 'Buat pengajuan' }}</button>
            <div x-show="o" x-cloak class="absolute left-0 right-0 z-50 mt-2 overflow-hidden rounded-xl border border-line bg-card py-1 text-sm shadow-lg">
                @foreach($baru as [$label, $url])
                    <a href="{{ $url }}" class="block px-4 py-2.5 hover:bg-soft">{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </div>

    <nav class="min-h-0 flex-1 space-y-4 overflow-y-auto px-3 py-2">
        @foreach($groups as $gname => $items)
            <div>
                <div class="px-3 pb-1 text-[11px] font-medium uppercase tracking-wider text-mute">{{ $gname }}</div>
                <div class="space-y-0.5">
                    @foreach($items as [$route, $label, $pattern, $icon])
                        @php $on = request()->routeIs($pattern); @endphp
                        <a href="{{ route($route) }}" class="nav {{ $on ? 'nav-on' : '' }}" @if($on) aria-current="page" @endif>
                            <x-icon :name="$icon" class="h-[18px] w-[18px] shrink-0" />
                            <span class="min-w-0 flex-1 truncate">{{ $label }}</span>
                            @if($route === 'inbox.index' && $inboxCount > 0)
                                <span class="rounded-full bg-brand px-1.5 py-0.5 text-[11px] font-medium leading-none text-white" title="{{ $inboxCount }} hal perlu diurus">{{ $inboxCount }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="shrink-0 border-t border-line p-3">
        <div class="flex items-center gap-3 px-1 pb-3">
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-soft text-sm font-semibold text-brand-text">{{ strtoupper(mb_substr($u->name, 0, 1)) }}</span>
            <div class="min-w-0">
                <div class="truncate text-sm font-medium">{{ $u->name }}</div>
                <div class="truncate text-xs text-mute">Masuk sebagai {{ $roleLabel }}</div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('profile.edit') }}" class="btn btn-secondary btn-sm"><x-icon name="user" class="h-4 w-4" /> Profil</a>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="btn btn-secondary btn-sm btn-block"><x-icon name="logout" class="h-4 w-4" /> Keluar</button>
            </form>
        </div>
    </div>
</aside>

<div class="lg:pl-64">
    {{-- Top bar --}}
    <header class="sticky top-0 z-20 border-b border-line bg-canvas/85 backdrop-blur print:hidden">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-x-2 gap-y-2 px-4 py-2.5 sm:px-6 lg:px-8">
            <button type="button" @click="open=true" class="btn btn-secondary btn-sm lg:hidden" aria-label="Buka menu"><x-icon name="menu" class="h-4 w-4" /></button>
            <a href="{{ route('dashboard') }}" class="mr-auto text-[15px] font-semibold tracking-tight lg:hidden">MaDocs</a>
            <form action="{{ route('documents.index') }}" class="order-last w-full sm:order-none sm:mr-auto sm:w-auto sm:min-w-[18rem] sm:max-w-md sm:flex-1" role="search">
                <div class="relative">
                    <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-mute" />
                    <input name="q" value="{{ request()->routeIs('documents.index') ? request('q') : '' }}" placeholder="Cari dokumen, mis. rapor kelas XII" class="input input-icon" aria-label="Cari dokumen">
                </div>
            </form>
            <a href="{{ route('help') }}" class="btn btn-ghost btn-sm" title="Buka panduan penggunaan"><x-icon name="help" class="h-4 w-4" /><span class="hidden sm:inline">Bantuan</span></a>
            <button type="button" onclick="mdToggleTheme()" class="btn btn-ghost btn-sm" aria-label="Ganti tema terang/gelap" title="Ganti tema terang/gelap">
                <x-icon name="moon" class="theme-moon h-4 w-4" /><x-icon name="sun" class="theme-sun h-4 w-4" />
            </button>
        </div>
    </header>

    <main id="konten" class="mx-auto max-w-6xl space-y-5 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="min-w-0">
                <h1 class="text-xl font-semibold tracking-tight sm:text-2xl">@yield('title', 'Beranda')</h1>
                <p class="sub mt-1 max-w-2xl text-sm text-mute">@yield('subtitle')</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 print:hidden">@yield('actions')</div>
        </div>

        @if(session('success'))
            <div class="flash flash-ok" role="status" x-data="{ show: true }" x-show="show">
                <x-icon name="check" class="mt-0.5 h-5 w-5 shrink-0" /><span class="flex-1">{{ session('success') }}</span>
                <button type="button" @click="show=false" aria-label="Tutup pesan"><x-icon name="x" class="h-4 w-4" /></button>
            </div>
        @endif
        @if(session('error'))
            <div class="flash flash-bad" role="alert" x-data="{ show: true }" x-show="show">
                <x-icon name="alert" class="mt-0.5 h-5 w-5 shrink-0" /><span class="flex-1">{{ session('error') }}</span>
                <button type="button" @click="show=false" aria-label="Tutup pesan"><x-icon name="x" class="h-4 w-4" /></button>
            </div>
        @endif
        @if($errors->any())
            <div class="flash flash-bad" role="alert">
                <x-icon name="alert" class="mt-0.5 h-5 w-5 shrink-0" />
                <span class="flex-1"><b class="font-medium">Ada isian yang belum benar.</b> Periksa kolom yang diberi pesan merah di bawah, perbaiki, lalu kirim lagi.</span>
            </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>
