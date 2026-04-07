<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Pertemuan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PertemuanSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = Kelas::all();

        $hariMap = [
            'Senin' => Carbon::MONDAY,
            'Selasa' => Carbon::TUESDAY,
            'Rabu' => Carbon::WEDNESDAY,
            'Kamis' => Carbon::THURSDAY,
            'Jumat' => Carbon::FRIDAY,
            'Sabtu' => Carbon::SATURDAY,
            'Minggu' => Carbon::SUNDAY,
        ];

        foreach ($kelasList as $kelas) {

            $targetDay = $hariMap[$kelas->hari] ?? Carbon::MONDAY;

            $startDate = Carbon::now()->startOfWeek();

            while ($startDate->dayOfWeek !== $targetDay) {
                $startDate->addDay();
            }

            for ($i = 1; $i <= 16; $i++) {

                $tanggal = $startDate->copy()->addWeeks($i - 1);

                // Topik
                $topik = match ($i) {
                    8 => 'UTS',
                    16 => 'UAS',
                    default => 'Pertemuan ' . $i,
                };

                // 🔥 STATUS LOGIC (REALISTIS)
                if ($i <= 4) {
                    // sudah lewat
                    $status = 'Selesai';
                    $started_at = $kelas->jam_mulai;
                    $ended_at = $kelas->jam_selesai;

                } elseif ($i === 5) {
                    // sedang berlangsung
                    $status = 'Berlangsung';

                    $started_at = Carbon::parse($kelas->jam_mulai)
                        ->addMinutes(5)
                        ->format('H:i:s');

                    $ended_at = null;

                } else {
                    // belum mulai
                    $status = 'Terjadwal';
                    $started_at = null;
                    $ended_at = null;
                }

                Pertemuan::updateOrCreate(
                    [
                        'kelas_id' => $kelas->id,
                        'pertemuan_ke' => $i,
                    ],
                    [
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'topik' => $topik,
                        'status' => $status,
                        'started_at' => $started_at,
                        'ended_at' => $ended_at,
                    ]
                );
            }
        }
    }
}