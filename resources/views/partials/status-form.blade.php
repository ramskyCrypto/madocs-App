<form method="POST" action="{{ $action }}" class="space-y-3">
    @csrf @method('PATCH')
    <div>
        <label for="status" class="label">Keputusan verifikasi</label>
        <select id="status" name="status" class="input">
            @foreach(['pending', 'approved', 'rejected'] as $s)
                <option value="{{ $s }}" @selected(old('status', $item->status) === $s)>{{ \App\Support\Label::status($s) }}</option>
            @endforeach
        </select>
        <p class="hint">Pilih <b>Disetujui</b> bila data sudah benar, atau <b>Ditolak</b> disertai alasan pada catatan.</p>
        @error('status')<p class="err" role="alert">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="catatan_admin" class="label">Catatan petugas</label>
        <textarea id="catatan_admin" name="catatan_admin" rows="2" class="input" placeholder="Opsional, mis. alasan penolakan agar siswa tahu">{{ old('catatan_admin', $item->catatan_admin) }}</textarea>
    </div>
    <button class="btn btn-primary btn-block">Simpan keputusan</button>
</form>
