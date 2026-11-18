<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    protected $guarded = [];
    protected $casts = ['ada_fisik' => 'boolean'];

    public function user()  { return $this->belongsTo(User::class); }
    public function jenis() { return $this->belongsTo(JenisDokumen::class, 'jenis_dokumen_id'); }
}
