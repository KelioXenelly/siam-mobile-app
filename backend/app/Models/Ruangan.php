<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangans';
    
    protected $fillable = [
        'nama',
        'kapasitas',
        'is_active',
    ];

    public function kelas() {
        return $this->hasMany(Kelas::class);
    }
}
