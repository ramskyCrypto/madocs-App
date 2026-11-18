<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\JenisDokumen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    private function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'jenis_dokumen_id' => 'required|exists:jenis_dokumens,id',
            'judul' => 'required|string|max:150',
            'tahun_ajaran' => 'nullable|string|max:20',
            'lokasi_arsip' => 'nullable|string|max:150',
            'keterangan' => 'nullable|string|max:500',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];
    }

    private function canAccess(Document $d): void
    {
        abort_unless(auth()->user()->isStaff() || $d->user_id === auth()->id(), 403);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $docs = Document::with('user', 'jenis')
            ->when(! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->when($request->jenis, fn ($q, $j) => $q->where('jenis_dokumen_id', $j))
            ->when($request->q, fn ($q, $s) => $q->where(function ($w) use ($s, $user) {
                $w->where('judul', 'like', "%$s%");
                if ($user->isStaff()) {
                    $w->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$s%")->orWhere('nis', 'like', "%$s%"));
                }
            }))
            ->latest()->paginate(10)->withQueryString();

        return view('documents.index', ['docs' => $docs, 'jenisList' => JenisDokumen::orderBy('nama')->get()]);
    }

    public function create()
    {
        return view('documents.form', $this->formData(new Document(['status_fisik' => 'tersedia'])));
    }

    private function formData(Document $document): array
    {
        return [
            'document' => $document,
            'siswa' => User::where('role', 'siswa')->orderBy('name')->get(),
            'jenisList' => JenisDokumen::orderBy('nama')->get(),
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['ada_fisik'] = $request->boolean('ada_fisik');
        $data['uploaded_by'] = auth()->id();
        unset($data['file']);
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('dokumen');
        }
        Document::create($data);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil disimpan ke repositori.');
    }

    public function show(Document $document)
    {
        $this->canAccess($document);

        return view('documents.show', ['document' => $document->load('user', 'jenis')]);
    }

    public function file(Request $request, Document $document)
    {
        $this->canAccess($document);
        abort_unless($document->file_path && Storage::exists($document->file_path), 404, 'File belum diunggah.');

        $name = str($document->judul)->slug().'.'.pathinfo($document->file_path, PATHINFO_EXTENSION);

        return $request->boolean('unduh')
            ? Storage::download($document->file_path, $name)
            : Storage::response($document->file_path, $name);
    }

    public function edit(Document $document)
    {
        return view('documents.form', $this->formData($document));
    }

    public function update(Request $request, Document $document)
    {
        $data = $request->validate($this->rules());
        $data['ada_fisik'] = $request->boolean('ada_fisik');
        unset($data['file']);
        if ($request->hasFile('file')) {
            if ($document->file_path) {
                Storage::delete($document->file_path);
            }
            $data['file_path'] = $request->file('file')->store('dokumen');
        }
        $document->update($data);

        return redirect()->route('documents.show', $document)->with('success', 'Dokumen diperbarui.');
    }

    public function destroy(Document $document)
    {
        if ($document->status_fisik === 'dipinjam') {
            return back()->with('error', 'Dokumen fisik sedang dipinjam, tidak dapat dihapus.');
        }
        if ($document->file_path) {
            Storage::delete($document->file_path);
        }
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Dokumen dihapus.');
    }
}
