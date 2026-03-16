<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan User dibuat berdasarkan Email yang unik
        $user = User::updateOrCreate(
            ['email' => 'eric@itbss.ac.id'], // Kriteria cari
            [
                'name' => 'Dr. Eric',
                'password' => Hash::make('password'),
                'role' => 'dosen'
            ]
        );

        // 2. Pastikan Dosen dibuat berdasarkan user_id atau NIDN yang unik
        Dosen::updateOrCreate(
            ['user_id' => $user->id], // Kriteria cari
            [
                'nidn' => '12345678',
            ]
        );
        }
}
