<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    
    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'kode_kelas',
        'semester',
        'tahun_ajaran',
        'ruangan',
    ];

    public function mataKuliah() {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen() {
        return $this->belongsTo(Dosen::class);
    }

    public function mahasiswas() {
        return $this->belongsToMany(KelasMahasiswa::class, 'kelas_mahasiswa', 'kelas_id', 'mahasiswa_id');
    }

    public function pertemuans() {
        return $this->hasMany(Pertemuan::class);
    }
}
