<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();
        $ruangans = Ruangan::all();

        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        foreach ($mataKuliahs as $i => $mk) {
            Kelas::updateOrCreate(
                ['kode_kelas' => 'A-' . $mk->kode_mk],
                [
                    'mata_kuliah_id' => $mk->id,
                    'dosen_id' => $dosens[$i % $dosens->count()]->id,
                    'ruangan_id' => $ruangans[$i % $ruangans->count()]->id,

                    'semester' => rand(1, 4),
                    'tahun_ajaran' => '2025/2026',

                    'hari' => $hariList[$i % count($hariList)],
                    'jam_mulai' => '08:00:00',
                    'jam_selesai' => '10:00:00',

                    'kapasitas' => 30,
                ]
            );
        }
    }
}
