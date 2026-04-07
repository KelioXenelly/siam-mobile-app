<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = [
            ['kode_prodi' => 'STI', 'nama_prodi' => 'Sistem dan Teknologi Informasi', 'jenjang' => 'S1', 'is_active' => true],
            ['kode_prodi' => 'BD', 'nama_prodi' => 'Bisnis Digital', 'jenjang' => 'S1', 'is_active' => true],
            ['kode_prodi' => 'KWU', 'nama_prodi' => 'Kewirausahaan', 'jenjang' => 'S1', 'is_active' => true],
        ];

        foreach ($prodis as $prodi) {
            Prodi::updateOrCreate(
                ['kode_prodi' => $prodi['kode_prodi']],
                $prodi
            );
        }
    }
}
