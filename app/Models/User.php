<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isStaff(): bool  { return in_array($this->role, ['admin', 'petugas'], true); }
    public function isSiswa(): bool  { return $this->role === 'siswa'; }

    public function documents()   { return $this->hasMany(Document::class); }
    public function peminjamans() { return $this->hasMany(Peminjaman::class); }
    public function legalisirs()  { return $this->hasMany(Legalisir::class); }
    public function ijazahs()     { return $this->hasMany(PengambilanIjazah::class); }
}
