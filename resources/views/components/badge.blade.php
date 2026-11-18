@props(['status'])
@php
    $map = [
        'pending' => 'bg-warn-soft text-warn-text',
        'approved' => 'bg-ok-soft text-ok-text',
        'rejected' => 'bg-bad-soft text-bad-text',
        'dikembalikan' => 'bg-brand-soft text-brand-text',
        'selesai' => 'bg-brand-soft text-brand-text',
        'diserahkan' => 'bg-brand-soft text-brand-text',
        'tersedia' => 'bg-ok-soft text-ok-text',
        'dipinjam' => 'bg-warn-soft text-warn-text',
    ];
@endphp
<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium whitespace-nowrap {{ $map[$status] ?? 'bg-soft text-mute' }}"><span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>{{ \App\Support\Label::status($status) }}</span>
