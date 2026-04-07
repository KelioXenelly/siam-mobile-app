<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodis';
    
    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
        'is_active',
    ];

    public function mahasiswas() {
        return $this->hasMany(Mahasiswa::class);
    }
}
