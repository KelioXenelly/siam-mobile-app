<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid unique constraint violations
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Absensi::truncate();
        \App\Models\SesiAbsensi::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $pertemuans = \App\Models\Pertemuan::all();
        $mahasiswas = \App\Models\Mahasiswa::all();

        if ($pertemuans->isEmpty() || $mahasiswas->isEmpty()) {
            return;
        }

        // We will distribute the meetings across the last 30 days
        $totalPertemuan = $pertemuans->count();
        
        foreach ($pertemuans as $index => $pertemuan) {
            // Distribute dates: earlier index = older date
            $daysAgo = 29 - floor(($index / $totalPertemuan) * 30);
            $date = \Carbon\Carbon::now()->subDays($daysAgo);
            
            // Skip weekends for aesthetics
            if ($date->isWeekend()) {
                $date->subDay(); // Move to Friday
            }

            $sesi = \App\Models\SesiAbsensi::create([
                'pertemuan_id' => $pertemuan->id,
                'qr_token' => \Illuminate\Support\Str::random(10),
                'latitude_dosen' => -6.17511,
                'longitude_dosen' => 106.86503,
                'radius_validasi' => 100,
                'expired_at' => $date->copy()->addHours(2),
                'is_closed' => true,
                'created_at' => $date->copy()->addHours(rand(8, 16)),
            ]);

            // Randomly pick some students to attend this session
            $numStudents = rand(10, min(25, $mahasiswas->count()));
            $attendingStudents = $mahasiswas->random($numStudents);

            foreach ($attendingStudents as $mhs) {
                \App\Models\Absensi::create([
                    'sesi_absensi_id' => $sesi->id,
                    'mahasiswa_id' => $mhs->id,
                    'latitude_mahasiswa' => -6.17511 + (rand(-5, 5) / 100000),
                    'longitude_mahasiswa' => 106.86503 + (rand(-5, 5) / 100000),
                    'selfie_photo' => 'selfies/dummy.png',
                    'status' => collect(['hadir', 'hadir', 'hadir', 'hadir', 'terlambat', 'izin', 'sakit'])->random(),
                    'waktu_absen' => $sesi->created_at->copy()->addMinutes(rand(1, 30)),
                    'created_at' => $sesi->created_at,
                ]);
            }
        }
    }
}
