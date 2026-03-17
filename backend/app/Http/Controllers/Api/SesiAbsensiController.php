<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\SesiAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SesiAbsensiController extends Controller
{
    public function generateQR(Request $request)
    {
        $validated = $request->validate([
            'pertemuan_id' => 'required|exists:pertemuans,id',
            'latitude_dosen' => 'required|decimal',
            'longitude_dosen' => 'required|decimal',
        ]);

        // hapus sesi lama jika ada (opsional)
        SesiAbsensi::where('pertemuan_id', $validated['pertemuan_id'])->delete();

        $token = Str::random(40);

        $sesi = SesiAbsensi::create([
            'pertemuan_id' => $validated['pertemuan_id'],
            'qr_token' => $token,
            'latitude_dosen' => $validated['latitude_dosen'],
            'longitude_dosen' => $validated['longitude_dosen'],
            'radius_validasi' => 50,
            'expired_at' => Carbon::now()->addMinutes(10),
        ]);

        // cek hanya dosen pemilik kelas yang boleh generate QR
        if ($sesi->pertemuan->kelas->dosen_id !== $request->user()->dosen->id) {
            return response()->json([
                'message' => 'Bukan kelas anda'
            ], 403);
        }

        // data yang akan di-encode ke QR
        $qrData = json_encode([
            'token' => $token
        ]);

        return response()->json([
            'success' => true,
            'message' => 'QR berhasil dibuat',
            'data' => [
                'qr_data' => $qrData,
                'expired_at' => $sesi->expired_at
            ]
        ], 201);
    }

    public function closeSesi($id)
    {
        $sesi = SesiAbsensi::with('pertemuan.kelas.mahasiswas')->findOrFail($id);

        $kelas = $sesi->pertemuan->kelas;
        $mahasiswas = $kelas->mahasiswas;

        foreach ($mahasiswas as $mhs) {

            $sudahAbsen = Absensi::where('sesi_absensi_id', $sesi->id)
                ->where('mahasiswa_id', $mhs->id)
                ->exists();

            if (!$sudahAbsen) {
                Absensi::create([
                    'sesi_absensi_id' => $sesi->id,
                    'mahasiswa_id' => $mhs->id,
                    'latitude_mahasiswa' => 0,
                    'longitude_mahasiswa' => 0,
                    'selfie_photo' => null,
                    'status' => 'alfa',
                    'waktu_absen' => now(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sesi ditutup, alfa berhasil digenerate'
        ], 200);
    }
}
