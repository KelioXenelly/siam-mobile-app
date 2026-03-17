<?php

use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\MataKuliahController;
use App\Http\Controllers\Api\PertemuanController;
use App\Http\Controllers\Api\SesiAbsensiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    Route::apiResource('mahasiswa', MahasiswaController::class);
    Route::apiResource('dosen', DosenController::class);
    Route::apiResource('kelas', KelasController::class);
    Route::post('kelas/{id}/assign-mahasiswa', [KelasController::class, 'assignMahasiswa']);
    Route::apiResource('mata-kuliah', MataKuliahController::class); // belum
    Route::apiResource('pertemuan', PertemuanController::class); // belum
    Route::get('/kelas/{id}/pertemuan', [PertemuanController::class, 'byKelas']); //belum
});

Route::middleware(['auth:sanctum','role:dosen'])->group(function () {
    Route::post('/generate-qr', [SesiAbsensiController::class,'generateQR']);
    Route::post('/sesi/{id}/close', [SesiAbsensiController::class, 'closeSesi']);
    Route::get('/kelas-saya', [KelasController::class,'kelasDosen']);
});

Route::middleware(['auth:sanctum','role:mahasiswa'])->group(function () {
    Route::post('/scan-absensi', [AbsensiController::class,'scan']);
    Route::get('/riwayat-absensi', [AbsensiController::class,'riwayat']);
});