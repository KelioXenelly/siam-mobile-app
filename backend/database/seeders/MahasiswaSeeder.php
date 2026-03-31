<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Cek User berdasarkan email (Kriteria Unik)
        $user = User::updateOrCreate(
            ['email' => 'kelio.xenelly@itbss.ac.id'], // Kriteria cari
            [
                'name' => 'Kelio Xenelly',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ]
        );

        // 2. Cek Mahasiswa berdasarkan NIM atau user_id (Kriteria Unik)
        Mahasiswa::updateOrCreate(
            ['nim' => '23110001'], // Kriteria cari (NIM biasanya unik)
            [
                'user_id' => $user->id,
                'prodi' => 'Sistem dan Teknologi Informasi',
                'angkatan' => '2023',
            ]
        );
        }
}
