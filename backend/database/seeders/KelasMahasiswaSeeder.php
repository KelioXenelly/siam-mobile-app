<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelasList = Kelas::with('mataKuliah')->get();
        $mahasiswas = Mahasiswa::all();

        foreach ($kelasList as $kelas) {
            foreach ($mahasiswas as $mhs) {

                // Ambil prefix kode MK (ST, BD, KW)
                $prefixMK = substr($kelas->mataKuliah->kode_mk, 0, 2);

                // Cocokkan dengan prodi mahasiswa
                $match = false;

                if ($prefixMK === 'ST' && $mhs->prodi->kode_prodi === 'STI') {
                    $match = true;
                }

                if ($prefixMK === 'BD' && $mhs->prodi->kode_prodi === 'BD') {
                    $match = true;
                }

                if ($prefixMK === 'KW' && $mhs->prodi->kode_prodi === 'KWU') {
                    $match = true;
                }

                if ($match) {
                    DB::table('kelas_mahasiswa')->updateOrInsert(
                        [
                            'kelas_id' => $kelas->id,
                            'mahasiswa_id' => $mhs->id,
                        ],
                        []
                    );
                }
            }
        }
    }
}
