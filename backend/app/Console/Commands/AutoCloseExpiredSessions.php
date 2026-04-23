<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SesiAbsensi;
use App\Services\AbsensiService;
use Carbon\Carbon;

class AutoCloseExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-close-expired-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close attendance sessions that have passed their expiration time';

    /**
     * Execute the console command.
     */
    public function handle(AbsensiService $absensiService)
    {
        $this->info('Checking for expired attendance sessions...');

        // Find sessions that are expired but not yet closed
        $expiredSessions = SesiAbsensi::where('is_closed', false)
            ->where('expired_at', '<', Carbon::now())
            ->get();

        if ($expiredSessions->isEmpty()) {
            $this->info('No expired sessions found.');
            return;
        }

        $this->info('Found ' . $expiredSessions->count() . ' expired sessions. Closing...');

        foreach ($expiredSessions as $sesi) {
            try {
                $absensiService->closeSession($sesi->id);
                $this->info("Session ID {$sesi->id} for Pertemuan ID {$sesi->pertemuan_id} closed successfully.");
            } catch (\Exception $e) {
                $this->error("Failed to close Session ID {$sesi->id}: " . $e->getMessage());
            }
        }

        $this->info('Processing completed.');
    }
}
