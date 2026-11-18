@props(['name', 'label', 'type' => 'text', 'value' => null, 'required' => false, 'hint' => null])
<div>
    <label for="{{ $name }}" class="label">{{ $label }} @if($required)<span class="text-bad-solid" title="Wajib diisi">*</span>@endif</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
           value="{{ $type === 'password' ? '' : old($name, $value) }}"
           {{ $attributes->merge(['class' => 'input'.($type === 'file' ? ' input-file' : '')]) }}>
    @if($hint)<p class="hint">{{ $hint }}</p>@endif
    @error($name)<p class="err" role="alert">{{ $message }}</p>@enderror
</div>
