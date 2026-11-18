<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Legalisir;
use App\Models\PengambilanIjazah;
use App\Models\Peminjaman;
use App\Models\User;
use App\Support\Inbox;

class DashboardController extends Controller
{
    public function index()
    {
        $u = auth()->user();
        $final = ['dikembalikan', 'diserahkan', 'selesai'];

        $rekap = collect([
            'Peminjaman Rapor' => Peminjaman::class,
            'Legalisir' => Legalisir::class,
            'Pengambilan Ijazah' => PengambilanIjazah::class,
        ])->map(function ($model) use ($u, $final) {
            $q = $model::query();
            if (! $u->isStaff()) {
                $q->where('user_id', $u->id);
            }
            $c = $q->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

            return [
                'pending' => $c['pending'] ?? 0,
                'approved' => $c['approved'] ?? 0,
                'rejected' => $c['rejected'] ?? 0,
                'selesai' => collect($final)->sum(fn ($s) => $c[$s] ?? 0),
            ];
        });

        $data = ['rekap' => $rekap];

        if ($u->isStaff()) {
            $inbox = Inbox::items();
            $data['inbox'] = $inbox;
            $data['stats'] = [
                'Kotak masuk' => $inbox->count(),
                'Mendesak' => $inbox->where('urgensi', 1)->count(),
                'Dokumen fisik dipinjam' => Document::where('status_fisik', 'dipinjam')->count(),
                'Siswa' => User::where('role', 'siswa')->count(),
            ];
        } else {
            $data['stats'] = [
                'Dokumen saya' => $u->documents()->count(),
                'Peminjaman aktif' => $u->peminjamans()->where('status', 'approved')->count(),
                'Menunggu verifikasi' => $rekap->sum('pending'),
            ];
            $data['ijazahAktif'] = $u->ijazahs()->where('status', 'approved')->latest()->first();
        }

        return view('dashboard', $data);
    }
}
