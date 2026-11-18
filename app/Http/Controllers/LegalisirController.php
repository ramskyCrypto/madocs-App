<?php

namespace App\Http\Controllers;

use App\Models\Legalisir;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegalisirController extends Controller
{
    private function canAccess(Legalisir $l): void
    {
        abort_unless(auth()->user()->isStaff() || $l->user_id === auth()->id(), 403);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $items = Legalisir::with('user')
            ->when(! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($user->isStaff() && $request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('kode', 'like', "%$s%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$s%")->orWhere('nis', 'like', "%$s%"))))
            ->latest()->paginate(10)->withQueryString();

        return view('legalisir.index', compact('items'));
    }

    public function create()
    {
        return view('legalisir.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis' => 'required|in:ijazah,rapor',
            'jumlah_rangkap' => 'required|integer|min:1|max:10',
            'keperluan' => 'required|string|max:500',
        ]);

        Legalisir::create($data + [
            'kode' => 'LGL-'.now()->format('ymd').'-'.strtoupper(Str::random(4)),
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()->route('legalisir.index')->with('success', 'Pengajuan legalisir terkirim.');
    }

    public function show(Legalisir $legalisir)
    {
        $this->canAccess($legalisir);

        return view('legalisir.show', ['l' => $legalisir->load('user', 'verifier')]);
    }

    public function status(Request $request, Legalisir $legalisir)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'catatan_admin' => 'nullable|string|max:500',
        ]);
        abort_if($legalisir->status === 'selesai', 403, 'Transaksi sudah selesai.');
        $legalisir->update($data + ['verified_by' => auth()->id()]);

        return back()->with('success', 'Status legalisir diperbarui.');
    }

    public function selesai(Request $request, Legalisir $legalisir)
    {
        abort_unless($legalisir->status === 'approved', 403, 'Legalisir harus berstatus Approved sebelum diserahkan.');
        $data = $request->validate(['diterima_oleh' => 'required|string|max:100']);

        $legalisir->update([
            'status' => 'selesai',
            'tgl_selesai' => now(),
            'diterima_oleh' => $data['diterima_oleh'],
            'verified_by' => auth()->id(),
        ]);

        return back()->with('success', 'Penyerahan dokumen legalisir dicatat.');
    }

    public function destroy(Legalisir $legalisir)
    {
        abort_unless($legalisir->user_id === auth()->id() && $legalisir->status === 'pending', 403);
        $legalisir->delete();

        return redirect()->route('legalisir.index')->with('success', 'Pengajuan dibatalkan.');
    }
}
