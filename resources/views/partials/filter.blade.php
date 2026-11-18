<form method="GET" class="flex flex-wrap items-center gap-2">
    @if($search ?? false)
        <div class="relative w-full sm:w-64">
            <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-mute" />
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode / nama / NIS" class="input input-icon" aria-label="Cari kode, nama, atau NIS">
        </div>
    @endif
    <select name="status" class="input w-full sm:w-auto" aria-label="Saring berdasarkan status">
        <option value="">Semua status</option>
        @foreach($statuses as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ \App\Support\Label::status($s) }}</option>@endforeach
    </select>
    <button class="btn btn-secondary">Terapkan filter</button>
    @if(request()->hasAny(['q','status']))<a href="{{ url()->current() }}" class="btn btn-ghost">Hapus filter</a>@endif
</form>
