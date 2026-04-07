<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Prodi;
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
        $prodiSTI = Prodi::where('kode_prodi', 'STI')->first();
        $prodiBD  = Prodi::where('kode_prodi', 'BD')->first();
        $prodiKWU = Prodi::where('kode_prodi', 'KWU')->first();

        $mahasiswas = [
            // STI (4 orang)
            ['email' => 'kelio.xenelly@itbss.ac.id', 'name' => 'Kelio Xenelly', 'nim' => '23110001', 'prodi_id' => $prodiSTI->id],
            ['email' => 'budi.santoso@itbss.ac.id', 'name' => 'Budi Santoso', 'nim' => '23110002', 'prodi_id' => $prodiSTI->id],
            ['email' => 'andi.prasetyo@itbss.ac.id', 'name' => 'Andi Prasetyo', 'nim' => '23110003', 'prodi_id' => $prodiSTI->id],
            ['email' => 'rina.selina@itbss.ac.id', 'name' => 'Rina Selina', 'nim' => '23110004', 'prodi_id' => $prodiSTI->id],

            // BD (4 orang)
            ['email' => 'siti.nurhaliza@itbss.ac.id', 'name' => 'Siti Nurhaliza', 'nim' => '23110005', 'prodi_id' => $prodiBD->id],
            ['email' => 'tono.wijaya@itbss.ac.id', 'name' => 'Tono Wijaya', 'nim' => '23110006', 'prodi_id' => $prodiBD->id],
            ['email' => 'dina.sari@itbss.ac.id', 'name' => 'Dina Sari', 'nim' => '23110007', 'prodi_id' => $prodiBD->id],
            ['email' => 'feri.prasetyo@itbss.ac.id', 'name' => 'Feri Prasetyo', 'nim' => '23110008', 'prodi_id' => $prodiBD->id],

            // KWU (4 orang)
            ['email' => 'yusuf.wijaya@itbss.ac.id', 'name' => 'Yusuf Wijaya', 'nim' => '23110009', 'prodi_id' => $prodiKWU->id],
            ['email' => 'lina.pratiwi@itbss.ac.id', 'name' => 'Lina Pratiwi', 'nim' => '23110010', 'prodi_id' => $prodiKWU->id],
            ['email' => 'agus.kusuma@itbss.ac.id', 'name' => 'Agus Kusuma', 'nim' => '23110011', 'prodi_id' => $prodiKWU->id],
            ['email' => 'putri.kusuma@itbss.ac.id', 'name' => 'Putri Kusuma', 'nim' => '23110012', 'prodi_id' => $prodiKWU->id],
        ];

        foreach ($mahasiswas as $mhs) {
            // 1. User
            $user = User::updateOrCreate(
                ['email' => $mhs['email']],
                [
                    'name' => $mhs['name'],
                    'password' => Hash::make('password'),
                    'role' => 'mahasiswa',
                    'is_active' => true,
                ]
            );

            // 2. Mahasiswa
            Mahasiswa::updateOrCreate(
                ['nim' => $mhs['nim']], // unik
                [
                    'user_id' => $user->id,
                    'prodi_id' => $mhs['prodi_id'],
                    'angkatan' => '2023',
                ]
            );
        }
    }
}
