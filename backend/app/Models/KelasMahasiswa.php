<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasMahasiswa extends Model
{
    protected $table = 'kelas_mahasiswa';
    
    protected $fillable = [
        'kelas_id',
        'mahasiswa_id',
    ];

    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }

    public function mahasiswa() {
        return $this->belongsTo(Mahasiswa::class);
    }
}
