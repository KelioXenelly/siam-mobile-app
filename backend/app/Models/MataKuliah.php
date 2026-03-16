<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliahs';
    
    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
    ];

    public function kelas() {
        return $this->hasMany(Kelas::class);
    }
}
