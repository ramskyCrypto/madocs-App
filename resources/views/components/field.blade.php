@props(['name', 'label', 'required' => false, 'hint' => null])
<div>
    <label for="{{ $name }}" class="label">{{ $label }} @if($required)<span class="text-bad-solid" title="Wajib diisi">*</span>@endif</label>
    {{ $slot }}
    @if($hint)<p class="hint">{{ $hint }}</p>@endif
    @error($name)<p class="err" role="alert">{{ $message }}</p>@enderror
</div>
