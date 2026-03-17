<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Absensi;
use App\Models\SesiAbsensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function scan(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required',
            'latitude_mahasiswa' => 'required',
            'longitude_mahasiswa' => 'required',
            'selfie_photo' => 'required|image|file:jpg,jpeg,png|max:20000'
        ]);

        $user = $request->user();
        $mahasiswa = $user->mahasiswa;

        // 1. cek sesi (validasi token)
        $sesi = SesiAbsensi::where('qr_token', $validated['token'])->first();

        if (!$sesi) {
            return response()->json([
                'success' => false,
                'message' => 'QR tidak valid'
            ], 400);
        }

        // 2. cek expired (validasi expired)
        if (Carbon::now()->greaterThan($sesi->expired_at)) {
            return response()->json([
                'success' => false,
                'message' => 'QR sudah expired'
            ], 400);
        }

        // 3. cek mahasiswa terdaftar di kelas (validasi mahasiswa terdaftar di kelas)
        $kelas = $sesi->pertemuan->kelas;

        $terdaftar = $kelas->mahasiswas()
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if (!$terdaftar) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak terdaftar di kelas ini'
            ], 403);
        }

        // 4. cek sudah absen?
        $already = Absensi::where('sesi_absensi_id', $sesi->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->exists();

        if ($already) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi',
            ], 400);
        }

        // 5. cek jarak (Haversine) -> validasi GPS
        $distance = $this->distance(
            $validated['latitude_mahasiswa'],
            $validated['longitude_mahasiswa'],
            $sesi->latitude_dosen,
            $sesi->longitude_dosen,
        );

        if ($distance > $sesi->radius_validasi) {
            return response()->json([
                'success' => false, 
                'message' => 'Di luar radius',
                'distance' => round($distance, 2) . ' meter',
            ], 400);
        }

        // 6. tentukan status (hadir / terlambat)
        $status = 'hadir';

        // jika lebih dari 10 menit = terlambat
        if (Carbon::now()->diffInMinutes($sesi->created_at) > 10) {
            $status = 'terlambat';
        }

        // 7. upload selfie
        if ($request->hasFile('selfie_photo')) {
            $file = $request->file('selfie_photo');

            $filename = time() . '_' . $file->getClientOriginalName();

            $path = $file->storeAs('selfies', $filename, 'public');
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Selfie wajib diupload'
            ], 400);
        }

        // 8. simpan absensi
        $absensi = Absensi::create([
            'sesi_absensi_id' => $sesi->id,
            'mahasiswa_id' => $mahasiswa->id,
            'latitude_mahasiswa' => $validated['latitude_mahasiswa'],
            'longitude_mahasiswa' => $validated['longitude_mahasiswa'],
            'selfie_photo' => $path,
            'status' => $status,
            'waktu_absen' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil',
            'data' => $absensi
        ], 201);
    }

    // fungsi hitung jarak (meter)
    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $earth = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earth * $c;
    }

    public function riwayat(Request $request)
    {
        $mahasiswa = $request->user()->mahasiswa;

        $absensi = Absensi::with('sesiAbsensi.pertemuan.kelas')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->get();

        if($absensi->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada riwayat absensi',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $absensi
        ], 200);
    }
}
