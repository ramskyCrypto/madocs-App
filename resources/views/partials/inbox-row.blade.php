@php
    $u = [
        1 => ['Mendesak', 'bg-bad-soft text-bad-text', 'bg-bad-solid'],
        2 => ['Perlu verifikasi', 'bg-warn-soft text-warn-text', 'bg-warn-text'],
        3 => ['Menunggu penyerahan', 'bg-brand-soft text-brand-text', 'bg-brand'],
    ][$it['urgensi']];
@endphp
<a href="{{ $it['url'] }}" class="flex items-start gap-3 px-4 py-3.5 transition hover:bg-soft">
    <span class="mt-2 h-2 w-2 shrink-0 rounded-full {{ $u[2] }}"></span>
    <div class="min-w-0 flex-1">
        <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
            <span class="font-medium">{{ $it['siswa']->name }}</span>
            <span class="text-xs text-mute">{{ $it['siswa']->kelas }}</span>
            <span class="rounded-full px-2 py-0.5 text-[11px] font-medium {{ $u[1] }}">{{ $u[0] }}</span>
        </div>
        <div class="mt-0.5 text-sm"><b class="font-medium">{{ $it['tindakan'] }}</b> &middot; {{ $it['judul'] }}</div>
        <div class="truncate text-xs text-mute">{{ $it['label'] }} &middot; {{ $it['info'] }}</div>
    </div>
    <div class="flex items-center gap-2 pt-0.5 text-xs text-mute">
        <span class="whitespace-nowrap">{{ $it['waktu']->diffForHumans() }}</span>
        <x-icon name="arrow" class="hidden h-4 w-4 sm:block" />
    </div>
</a>
