<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengambilanIjazah extends Model
{
    protected $table = 'pengambilan_ijazahs';
    protected $guarded = [];
    protected $casts = [
        'jadwal_tanggal' => 'date',
        'diwakilkan' => 'boolean',
        'tgl_diserahkan' => 'datetime',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }
    public function penyerah() { return $this->belongsTo(User::class, 'diserahkan_oleh'); }
}
