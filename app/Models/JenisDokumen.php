<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisDokumen extends Model
{
    protected $table = 'jenis_dokumens';
    protected $guarded = [];

    public function documents() { return $this->hasMany(Document::class); }
}
