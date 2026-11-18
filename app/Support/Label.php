<?php

namespace App\Support;

/**
 * Teks tampilan yang mudah dipahami pemula. Nilai di database (pending, approved, ...)
 * TIDAK berubah; hanya label yang ditampilkan ke pengguna.
 */
class Label
{
    public const STATUS = [
        'pending' => 'Menunggu verifikasi',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'dikembalikan' => 'Dikembalikan',
        'selesai' => 'Selesai',
        'diserahkan' => 'Diserahkan',
        'tersedia' => 'Tersedia',
        'dipinjam' => 'Dipinjam',
    ];

    public static function status(?string $s): string
    {
        return self::STATUS[$s] ?? ucfirst((string) $s);
    }
}
