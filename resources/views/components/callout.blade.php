@props(['type' => 'info', 'title' => null])
@php
    $c = ['info' => 'bg-brand-soft text-brand-text border-brand/20', 'tip' => 'bg-ok-soft text-ok-text border-ok-solid/25', 'warn' => 'bg-warn-soft text-warn-text border-warn-text/25'];
    $i = ['info' => 'info', 'tip' => 'bulb', 'warn' => 'alert'];
@endphp
<div {{ $attributes->merge(['class' => 'flex gap-3 rounded-xl border p-3.5 text-sm '.($c[$type] ?? $c['info'])]) }}>
    <x-icon :name="$i[$type] ?? 'info'" class="mt-0.5 h-5 w-5 shrink-0" />
    <div class="min-w-0 space-y-1">
        @if($title)<div class="font-medium">{{ $title }}</div>@endif
        <div class="text-ink">{{ $slot }}</div>
    </div>
</div>
