<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangans = [
            ['nama' => '1.01', 'kapasitas' => 40, 'is_active' => true],
            ['nama' => '1.02', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '1.03', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '1.04', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '1.05', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '2.01', 'kapasitas' => 40, 'is_active' => true],
            ['nama' => '2.02', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '2.03', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '2.04', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '2.05', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '3.01', 'kapasitas' => 40, 'is_active' => true],
            ['nama' => '3.02', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '3.03', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '3.04', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '3.05', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '4.01', 'kapasitas' => 40, 'is_active' => true],
            ['nama' => '4.02', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '4.03', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '4.04', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => '4.05', 'kapasitas' => 35, 'is_active' => true],
            ['nama' => 'LAB-1', 'kapasitas' => 40, 'is_active' => true],
            ['nama' => 'LAB-2', 'kapasitas' => 40, 'is_active' => true],
            ['nama' => 'LAB-XR', 'kapasitas' => 30, 'is_active' => true],
        ];

        foreach ($ruangans as $r) {
            Ruangan::updateOrCreate(
                ['nama' => $r['nama']],
                $r
            );
        }
    }
}
