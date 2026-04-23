<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_absensi_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('latitude_mahasiswa', 10, 7);
            $table->decimal('longitude_mahasiswa', 10, 7);
            $table->string('selfie_photo')->default('-');
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'alfa'])->default('hadir');
            $table->timestamp('waktu_absen')->useCurrent();
            $table->unique(['sesi_absensi_id','mahasiswa_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
