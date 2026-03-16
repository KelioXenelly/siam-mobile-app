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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('dosen_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('kode_kelas');
            $table->unsignedTinyInteger('semester');
            $table->string('tahun_ajaran');
            $table->string('ruangan');
            $table->unique(['mata_kuliah_id', 'kode_kelas', 'semester', 'tahun_ajaran']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
