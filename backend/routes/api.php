<?php

use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\MataKuliahController;
use App\Http\Controllers\Api\PertemuanController;
use App\Http\Controllers\Api\ProdiController;
use App\Http\Controllers\Api\RuanganController;
use App\Http\Controllers\Api\SesiAbsensiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

Route::middleware(['auth:sanctum','role:admin'])->group(function () {
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/users', [AuthController::class, 'users']);
    Route::put('/users/{user_id}', [AuthController::class, 'updateUser']);
    Route::delete('/users/{user_id}', [AuthController::class, 'deleteUser']);

    Route::apiResource('/program-studi', ProdiController::class);

    Route::apiResource('/ruangan', RuanganController::class);

    Route::apiResource('/mata-kuliah', MataKuliahController::class);
    
    Route::apiResource('/kelas', KelasController::class);
    Route::post('/kelas/{kelas_id}/assign-mahasiswa', [KelasController::class, 'assignMahasiswa']);
    Route::apiResource('/pertemuan', PertemuanController::class);
});

Route::middleware(['auth:sanctum','role:dosen'])->group(function () {
    Route::post('/generate-qr', [SesiAbsensiController::class,'generateQR']);
    Route::post('/sesi/{sesi_id}/close', [SesiAbsensiController::class, 'closeSesi']);
    Route::get('/sesi/{sesi_id}', [SesiAbsensiController::class, 'show']);
    Route::get('/sesi/{sesi_id}/absensi', [AbsensiController::class, 'bySesi']);
    Route::get('/pertemuan/{pertemuan_id}/sesi', [SesiAbsensiController::class, 'byPertemuan']);
    Route::get('/pertemuan/{pertemuan_id}/sesi-aktif', [SesiAbsensiController::class, 'aktif']);
    Route::get('/kelas-saya', [KelasController::class,'kelasDosen']);
    Route::get('/kelas/{kelas_id}/pertemuan', [PertemuanController::class, 'byKelas']);
});

Route::middleware(['auth:sanctum','role:mahasiswa'])->group(function () {
    Route::post('/scan-absensi', [AbsensiController::class,'scan']);
    Route::get('/riwayat-absensi', [AbsensiController::class,'riwayat']);
});