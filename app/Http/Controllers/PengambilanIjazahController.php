<?php

namespace App\Http\Controllers;

use App\Models\PengambilanIjazah;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PengambilanIjazahController extends Controller
{
    public const JAM = ['08:00 - 09:00', '09:00 - 10:00', '10:00 - 11:00', '11:00 - 12:00', '13:00 - 14:00', '14:00 - 15:00'];
    public const KUOTA = 5;

    private function canAccess(PengambilanIjazah $i): void
    {
        abort_unless(auth()->user()->isStaff() || $i->user_id === auth()->id(), 403);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $items = PengambilanIjazah::with('user')
            ->when(! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($user->isStaff() && $request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('kode', 'like', "%$s%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$s%")->orWhere('nis', 'like', "%$s%"))))
            ->latest()->paginate(10)->withQueryString();

        return view('ijazah.index', compact('items'));
    }

    public function create()
    {
        return view('ijazah.create', ['jamList' => self::JAM]);
    }

    public function store(Request $request)
    {
        $wakil = $request->boolean('diwakilkan');

        $data = $request->validate([
            'jadwal_tanggal' => ['required', 'date', 'after_or_equal:today', function ($attr, $value, $fail) {
                if (Carbon::parse($value)->isWeekend()) {
                    $fail('Pengambilan hanya dilayani pada hari kerja (Senin-Jumat).');
                }
            }],
            'jadwal_jam' => ['required', Rule::in(self::JAM)],
            'nama_pengambil' => [Rule::requiredIf($wakil), 'nullable', 'string', 'max:100'],
            'hubungan' => [Rule::requiredIf($wakil), 'nullable', 'string', 'max:50'],
            'no_identitas' => [Rule::requiredIf($wakil), 'nullable', 'string', 'max:30'],
            'catatan' => 'nullable|string|max:500',
        ]);

        $sudah = PengambilanIjazah::where('user_id', auth()->id())->whereIn('status', ['pending', 'approved', 'diserahkan'])->exists();
        if ($sudah) {
            throw ValidationException::withMessages(['jadwal_tanggal' => 'Anda sudah memiliki pengajuan aktif atau ijazah sudah diserahkan.']);
        }

        $terisi = PengambilanIjazah::whereDate('jadwal_tanggal', $data['jadwal_tanggal'])
            ->where('jadwal_jam', $data['jadwal_jam'])->whereIn('status', ['pending', 'approved'])->count();
        if ($terisi >= self::KUOTA) {
            throw ValidationException::withMessages(['jadwal_jam' => 'Kuota sesi ini penuh, pilih jam/tanggal lain.']);
        }

        if (! $wakil) {
            $data['nama_pengambil'] = $data['hubungan'] = $data['no_identitas'] = null;
        }

        PengambilanIjazah::create($data + [
            'kode' => 'IJZ-'.now()->format('ymd').'-'.strtoupper(Str::random(4)),
            'user_id' => auth()->id(),
            'diwakilkan' => $wakil,
            'status' => 'pending',
        ]);

        return redirect()->route('ijazah.index')->with('success', 'Jadwal pengambilan ijazah diajukan.');
    }

    public function show(PengambilanIjazah $ijazah)
    {
        $this->canAccess($ijazah);

        return view('ijazah.show', ['i' => $ijazah->load('user', 'verifier', 'penyerah')]);
    }

    public function status(Request $request, PengambilanIjazah $ijazah)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'catatan_admin' => 'nullable|string|max:500',
        ]);
        abort_if($ijazah->status === 'diserahkan', 403, 'Ijazah sudah diserahkan.');

        if ($data['status'] === 'approved' && ! $ijazah->kode_validasi) {
            $data['kode_validasi'] = 'MD'.random_int(1000, 9999);
        }
        if ($data['status'] !== 'approved') {
            $data['kode_validasi'] = null;
        }
        $ijazah->update($data + ['verified_by' => auth()->id()]);

        return back()->with('success', 'Status pengambilan ijazah diperbarui.');
    }

    public function serahkan(Request $request, PengambilanIjazah $ijazah)
    {
        abort_unless($ijazah->status === 'approved', 403, 'Pengajuan harus berstatus Approved.');
        $data = $request->validate([
            'kode_validasi' => 'required|string|max:10',
            'nama_penerima' => 'required|string|max:100',
            'no_identitas_penerima' => 'required|string|max:30',
        ]);

        if (strtoupper(trim($data['kode_validasi'])) !== strtoupper($ijazah->kode_validasi)) {
            throw ValidationException::withMessages(['kode_validasi' => 'Kode validasi tidak cocok.']);
        }

        $harusNama = $ijazah->diwakilkan ? $ijazah->nama_pengambil : $ijazah->user->name;
        if (mb_strtolower(trim($data['nama_penerima'])) !== mb_strtolower(trim($harusNama))) {
            throw ValidationException::withMessages(['nama_penerima' => "Nama penerima harus sesuai pengajuan ($harusNama)."]);
        }
        if ($ijazah->diwakilkan && trim($data['no_identitas_penerima']) !== trim($ijazah->no_identitas)) {
            throw ValidationException::withMessages(['no_identitas_penerima' => 'Nomor identitas tidak sesuai dengan data pengajuan.']);
        }

        $ijazah->update([
            'status' => 'diserahkan',
            'tgl_diserahkan' => now(),
            'nama_penerima' => $data['nama_penerima'],
            'no_identitas_penerima' => $data['no_identitas_penerima'],
            'diserahkan_oleh' => auth()->id(),
        ]);

        return back()->with('success', 'Penyerahan ijazah asli tervalidasi dan tercatat.');
    }

    public function destroy(PengambilanIjazah $ijazah)
    {
        abort_unless($ijazah->user_id === auth()->id() && $ijazah->status === 'pending', 403);
        $ijazah->delete();

        return redirect()->route('ijazah.index')->with('success', 'Pengajuan dibatalkan.');
    }
}
