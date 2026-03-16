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
        Schema::create('pertemuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('pertemuan_ke');
            $table->date('tanggal');
            $table->unique(['kelas_id','pertemuan_ke']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertemuans');
    }
};
