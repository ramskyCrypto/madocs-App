@props(['icon' => 'file', 'title'])
<div class="px-6 py-12 text-center">
    <div class="mx-auto mb-3 grid h-11 w-11 place-items-center rounded-full bg-soft text-mute"><x-icon :name="$icon" class="h-5 w-5" /></div>
    <div class="font-medium">{{ $title }}</div>
    <div class="mx-auto mt-1 max-w-sm space-y-3 text-sm text-mute">{{ $slot }}</div>
</div>
