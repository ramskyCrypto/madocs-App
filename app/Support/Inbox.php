<?php

namespace App\Support;

use App\Models\Legalisir;
use App\Models\PengambilanIjazah;
use App\Models\Peminjaman;
use Illuminate\Support\Collection;

/**
 * Kotak masuk staf: semua hal yang masih harus diurus
 * (verifikasi pengajuan + penyerahan/pengembalian yang tertunda).
 * Urgensi: 1 = mendesak, 2 = perlu verifikasi, 3 = menunggu penyerahan.
 */
class Inbox
{
    private const AKTIF = ['pending', 'approved'];

    public static function items(): Collection
    {
        $items = collect();

        Peminjaman::with('user', 'document')->whereIn('status', self::AKTIF)->get()->each(function ($p) use ($items) {
            $pending = $p->status === 'pending';
            $items->push([
                'jenis' => 'peminjaman',
                'label' => 'Peminjaman Rapor',
                'urgensi' => $pending ? 2 : ($p->terlambat ? 1 : 3),
                'tindakan' => $pending ? 'Verifikasi pengajuan' : ($p->terlambat ? 'Terlambat, catat pengembalian' : 'Catat pengembalian'),
                'judul' => $p->document->judul,
                'info' => $pending ? 'Pinjam '.$p->tgl_pinjam->format('d/m/Y') : 'Jatuh tempo '.$p->tgl_kembali_rencana->format('d/m/Y'),
                'siswa' => $p->user,
                'waktu' => $p->updated_at,
                'url' => route('peminjaman.show', $p),
            ]);
        });

        Legalisir::with('user')->whereIn('status', self::AKTIF)->get()->each(function ($l) use ($items) {
            $pending = $l->status === 'pending';
            $items->push([
                'jenis' => 'legalisir',
                'label' => 'Legalisir',
                'urgensi' => $pending ? 2 : 3,
                'tindakan' => $pending ? 'Verifikasi pengajuan' : 'Serahkan dokumen legalisir',
                'judul' => 'Legalisir '.ucfirst($l->jenis).' ('.$l->jumlah_rangkap.' rangkap)',
                'info' => $l->keperluan,
                'siswa' => $l->user,
                'waktu' => $l->updated_at,
                'url' => route('legalisir.show', $l),
            ]);
        });

        PengambilanIjazah::with('user')->whereIn('status', self::AKTIF)->get()->each(function ($i) use ($items) {
            $pending = $i->status === 'pending';
            $hariIni = ! $pending && ($i->jadwal_tanggal->isToday() || $i->jadwal_tanggal->isPast());
            $items->push([
                'jenis' => 'ijazah',
                'label' => 'Pengambilan Ijazah',
                'urgensi' => $pending ? 2 : ($hariIni ? 1 : 3),
                'tindakan' => $pending ? 'Verifikasi jadwal pengambilan' : ($hariIni ? 'Jadwal hari ini, validasi & serahkan' : 'Siapkan ijazah asli'),
                'judul' => 'Ijazah asli'.($i->diwakilkan ? ' (diwakilkan)' : ''),
                'info' => 'Jadwal '.$i->jadwal_tanggal->format('d/m/Y').', '.$i->jadwal_jam,
                'siswa' => $i->user,
                'waktu' => $i->updated_at,
                'url' => route('ijazah.show', $i),
            ]);
        });

        return $items->sortBy([['urgensi', 'asc'], ['waktu', 'asc']])->values();
    }

    public static function count(): int
    {
        return Peminjaman::whereIn('status', self::AKTIF)->count()
            + Legalisir::whereIn('status', self::AKTIF)->count()
            + PengambilanIjazah::whereIn('status', self::AKTIF)->count();
    }
}
