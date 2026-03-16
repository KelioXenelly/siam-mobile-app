<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiAbsensi extends Model
{
    protected $table = 'sesi_absensis';

    protected $fillable = [
        'pertemuan_id',
        'qr_token',
        'latitude_dosen',
        'longitude_dosen',
        'expired_at',
    ];

    public function pertemuan() {
        return $this->belongsTo(Pertemuan::class);
    }

    public function absensis() {
        return $this->hasMany(Absensi::class);
    }
}
