<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    protected $table = 'pertemuans';
    
    protected $fillable = [
        'kelas_id',
        'pertemuan_ke',
        'tanggal',
        'topik',
        'status',
        'started_at',
        'ended_at',
    ];

    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }

    public function sesiAbsensi() {
        return $this->hasOne(SesiAbsensi::class);
    }
}
