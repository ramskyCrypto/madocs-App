<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Legalisir extends Model
{
    protected $table = 'legalisirs';
    protected $guarded = [];
    protected $casts = ['tgl_selesai' => 'datetime'];

    public function user()     { return $this->belongsTo(User::class); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }
}
