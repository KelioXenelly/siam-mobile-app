<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswas';
    
    protected $fillable = [
        'user_id',
        'nim',
        'prodi',
        'angkatan',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function kelas() {
        return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa', 'mahasiswa_id', 'kelas_id');
    }

    public function absensis() {
        return $this->hasMany(Absensi::class);
    }
}
