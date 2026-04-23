<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'user_id' => 2, // Dosen Dr. Budi (Eric)
                'action' => 'Dosen mengupdate absen',
                'detail' => 'Kelas Pemrograman Web A - Pertemuan 4',
                'status' => 'success',
                'created_at' => now()->subMinutes(10),
            ],
            [
                'user_id' => 1, // Admin
                'action' => 'Mahasiswa baru ditambahkan',
                'detail' => 'NIM 24001 - Andi Setiawan (Teknik Informatika)',
                'status' => 'info',
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => 2,
                'action' => 'Pertemuan dibuat',
                'detail' => 'Kelas Struktur Data B - Pertemuan 1',
                'status' => 'success',
                'created_at' => now()->subHours(3),
            ],
            [
                'user_id' => 3,
                'action' => 'Dosen gagal scan QR',
                'detail' => 'Sistem mengalami timeout selama 2 detik',
                'status' => 'warning',
                'created_at' => now()->subDay(),
            ],
            [
                'user_id' => 1,
                'action' => 'Data Kelas diimpor',
                'detail' => '30 kelas baru ditambahkan via CSV',
                'status' => 'info',
                'created_at' => now()->subDays(2),
            ],
        ];

        foreach ($activities as $activity) {
            \App\Models\ActivityLog::create($activity);
        }
    }
}
