<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['ST001', 'Pengantar TI'],
            ['ST002', 'Algoritma'],
            ['ST003', 'Basis Data'],
            ['ST004', 'Pemrograman Web'],

            ['BD001', 'Bisnis Digital'],
            ['BD002', 'E-Commerce'],
            ['BD003', 'Digital Marketing'],
            ['BD004', 'Startup'],

            ['KW001', 'Kewirausahaan'],
            ['KW002', 'Manajemen Usaha'],
            ['KW003', 'Inovasi'],
            ['KW004', 'Strategi Bisnis'],
        ];

        foreach ($data as [$kode, $nama]) {
            MataKuliah::updateOrCreate(
                ['kode_mk' => $kode],
                ['nama_mk' => $nama, 'sks' => 3]
            );
        }
    }
}
