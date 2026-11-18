<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    protected $guarded = [];
    protected $casts = [
        'tgl_pinjam' => 'date',
        'tgl_kembali_rencana' => 'date',
        'tgl_dikembalikan' => 'date',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function document() { return $this->belongsTo(Document::class); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }

    public function getTerlambatAttribute(): bool
    {
        return $this->status === 'approved' && $this->tgl_kembali_rencana->isPast() && ! $this->tgl_kembali_rencana->isToday();
    }
}
