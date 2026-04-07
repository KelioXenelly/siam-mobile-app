<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    
    protected $fillable = [
        'kode_kelas',
        'mata_kuliah_id',
        'dosen_id',
        'ruangan_id',
        'semester',
        'tahun_ajaran',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kapasitas',
    ];

    public function mataKuliah() {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen() {
        return $this->belongsTo(Dosen::class);
    }

    public function ruangan() {
        return $this->belongsTo(Ruangan::class);
    }

    public function mahasiswas() {
        return $this->belongsToMany(Mahasiswa::class, 'kelas_mahasiswa', 'kelas_id', 'mahasiswa_id');
    }

    public function pertemuans() {
        return $this->hasMany(Pertemuan::class);
    }
}
