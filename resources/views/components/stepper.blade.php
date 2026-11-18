@props(['flow', 'status'])
@php
    $flows = [
        'peminjaman' => ['Diajukan', 'Diverifikasi', 'Dipinjam', 'Dikembalikan'],
        'legalisir' => ['Diajukan', 'Diverifikasi', 'Diserahkan'],
        'ijazah' => ['Diajukan', 'Diverifikasi', 'Diserahkan'],
    ];
    $pos = ['pending' => 1, 'approved' => 2, 'dikembalikan' => 4, 'selesai' => 3, 'diserahkan' => 3];
    $steps = $flows[$flow];
    $rej = $status === 'rejected';
    $p = $pos[$status] ?? 0;
@endphp
<ol class="flex items-start" aria-label="Tahapan pengajuan">
@foreach($steps as $k => $s)
    @php
        $state = $rej ? ($k === 0 ? 'done' : ($k === 1 ? 'bad' : 'todo')) : ($k < $p ? 'done' : ($k === $p ? 'now' : 'todo'));
        $label = ($rej && $k === 1) ? 'Ditolak' : $s;
        $dotCls = ['done' => 'bg-brand text-white', 'now' => 'bg-brand-soft text-brand-text ring-2 ring-brand', 'bad' => 'bg-bad-solid text-white', 'todo' => 'bg-soft text-mute'][$state];
        $lineCls = ['done' => 'bg-brand', 'now' => 'bg-brand', 'bad' => 'bg-bad-solid', 'todo' => 'bg-line2'][$state];
    @endphp
    <li class="relative min-w-0 flex-1 text-center">
        @if($k > 0)<span class="absolute right-1/2 top-3.5 h-px w-full {{ $lineCls }}"></span>@endif
        <span class="relative z-10 mx-auto grid h-7 w-7 place-items-center rounded-full text-xs font-semibold {{ $dotCls }}">
            @if($state === 'done')<x-icon name="check" class="h-4 w-4" />@elseif($state === 'bad')<x-icon name="x" class="h-4 w-4" />@else{{ $k + 1 }}@endif
        </span>
        <span class="mt-1.5 block px-1 text-[11px] leading-tight sm:text-xs {{ $state === 'now' ? 'font-medium text-ink' : ($state === 'bad' ? 'font-medium text-bad-text' : 'text-mute') }}">{{ $label }}</span>
    </li>
@endforeach
</ol>
