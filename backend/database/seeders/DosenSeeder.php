<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
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
        $dosens = [
            ['email' => 'eric@itbss.ac.id', 'name' => 'Eric Prakarsa', 'nidn' => '10000001'],
            ['email' => 'susan@itbss.ac.id', 'name' => 'Susani', 'nidn' => '10000002'],
            ['email' => 'ahmad@itbss.ac.id', 'name' => 'Ahmad Jayanto', 'nidn' => '10000003'],
            ['email' => 'rina@itbss.ac.id', 'name' => 'Rina Selina', 'nidn' => '10000004'],
            ['email' => 'bambang@itbss.ac.id', 'name' => 'Bambang Budi', 'nidn' => '10000005'],
            ['email' => 'lina@itbss.ac.id', 'name' => 'Lina Pratiwi', 'nidn' => '10000006'],
            ['email' => 'dewi@itbss.ac.id', 'name' => 'Dewi Kusuma', 'nidn' => '10000007'],
            ['email' => 'yusuf@itbss.ac.id', 'name' => 'Yusuf Wijaya', 'nidn' => '10000008'],
        ];

        foreach ($dosens as $dsn) {
            $user = User::updateOrCreate(
                ['email' => $dsn['email']],
                [
                    'name' => $dsn['name'],
                    'password' => Hash::make('password'),
                    'role' => 'dosen',
                    'is_active' => true,
                ]
            );

            Dosen::updateOrCreate(
                ['nidn' => $dsn['nidn']],
                ['user_id' => $user->id]
            );
        }
    }
}
