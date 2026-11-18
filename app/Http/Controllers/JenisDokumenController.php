<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisDokumenController extends Controller
{
    public function index()
    {
        return view('jenis.index', ['items' => JenisDokumen::withCount('documents')->orderBy('nama')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode' => 'required|string|max:20|unique:jenis_dokumens,kode',
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);
        JenisDokumen::create($data);

        return back()->with('success', 'Jenis dokumen ditambahkan.');
    }

    public function update(Request $request, JenisDokumen $jenis)
    {
        $data = $request->validate([
            'kode' => ['required', 'string', 'max:20', Rule::unique('jenis_dokumens', 'kode')->ignore($jenis->id)],
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);
        $jenis->update($data);

        return back()->with('success', 'Jenis dokumen diperbarui.');
    }

    public function destroy(JenisDokumen $jenis)
    {
        if ($jenis->documents()->exists()) {
            return back()->with('error', 'Tidak dapat dihapus: masih dipakai oleh dokumen.');
        }
        $jenis->delete();

        return back()->with('success', 'Jenis dokumen dihapus.');
    }
}
