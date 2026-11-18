<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PeminjamanController extends Controller
{
    private function canAccess(Peminjaman $p): void
    {
        abort_unless(auth()->user()->isStaff() || $p->user_id === auth()->id(), 403);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $items = Peminjaman::with('user', 'document')
            ->when(! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($user->isStaff() && $request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('kode', 'like', "%$s%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$s%")->orWhere('nis', 'like', "%$s%"))))
            ->latest()->paginate(10)->withQueryString();

        return view('peminjaman.index', compact('items'));
    }

    public function create()
    {
        $documents = Document::with('jenis')
            ->where('user_id', auth()->id())
            ->where('ada_fisik', true)
            ->where('status_fisik', 'tersedia')
            ->orderBy('judul')->get();

        return view('peminjaman.create', compact('documents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'document_id' => 'required|exists:documents,id',
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali_rencana' => 'required|date|after:tgl_pinjam|before_or_equal:'.now()->addDays(14)->toDateString(),
            'tujuan' => 'required|string|max:500',
        ], [
            'tgl_kembali_rencana.before_or_equal' => 'Maksimal masa peminjaman 14 hari dari hari ini.',
        ]);

        $doc = Document::where('id', $data['document_id'])->where('user_id', auth()->id())->where('ada_fisik', true)->first();
        if (! $doc || $doc->status_fisik !== 'tersedia') {
            throw ValidationException::withMessages(['document_id' => 'Dokumen fisik tidak tersedia untuk dipinjam.']);
        }
        $aktif = Peminjaman::where('document_id', $doc->id)->whereIn('status', ['pending', 'approved'])->exists();
        if ($aktif) {
            throw ValidationException::withMessages(['document_id' => 'Sudah ada pengajuan aktif untuk dokumen ini.']);
        }

        Peminjaman::create($data + [
            'kode' => 'PJM-'.now()->format('ymd').'-'.strtoupper(Str::random(4)),
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan peminjaman terkirim, menunggu verifikasi petugas.');
    }

    public function show(Peminjaman $peminjaman)
    {
        $this->canAccess($peminjaman);

        return view('peminjaman.show', ['p' => $peminjaman->load('user', 'document.jenis', 'verifier')]);
    }

    public function status(Request $request, Peminjaman $peminjaman)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'catatan_admin' => 'nullable|string|max:500',
        ]);
        abort_if($peminjaman->status === 'dikembalikan', 403, 'Transaksi sudah selesai.');

        DB::transaction(function () use ($peminjaman, $data) {
            $doc = $peminjaman->document;

            if ($data['status'] === 'approved' && $peminjaman->status !== 'approved') {
                if ($doc->status_fisik === 'dipinjam') {
                    throw ValidationException::withMessages(['status' => 'Dokumen fisik sedang dipinjam pihak lain.']);
                }
                $doc->update(['status_fisik' => 'dipinjam']);
            }
            if ($data['status'] !== 'approved' && $peminjaman->status === 'approved') {
                $doc->update(['status_fisik' => 'tersedia']);
            }

            $peminjaman->update($data + ['verified_by' => auth()->id()]);
        });

        return back()->with('success', 'Status peminjaman diperbarui.');
    }

    public function kembali(Request $request, Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->status === 'approved', 403, 'Hanya peminjaman berstatus Approved yang dapat dikembalikan.');
        $data = $request->validate(['catatan_admin' => 'nullable|string|max:500']);

        DB::transaction(function () use ($peminjaman, $data) {
            $peminjaman->document->update(['status_fisik' => 'tersedia']);
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tgl_dikembalikan' => now()->toDateString(),
                'catatan_admin' => $data['catatan_admin'] ?? $peminjaman->catatan_admin,
                'verified_by' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Pengembalian dokumen fisik berhasil dicatat.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        abort_unless($peminjaman->user_id === auth()->id() && $peminjaman->status === 'pending', 403);
        $peminjaman->delete();

        return redirect()->route('peminjaman.index')->with('success', 'Pengajuan dibatalkan.');
    }
}
