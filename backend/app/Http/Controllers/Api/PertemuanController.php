<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Pertemuan;
use Illuminate\Http\Request;

class PertemuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pertemuans = Pertemuan::query()
            ->join('kelas', 'pertemuans.kelas_id', '=', 'kelas.id')
            ->join('mata_kuliahs', 'kelas.mata_kuliah_id', '=', 'mata_kuliahs.id')
            ->with(['kelas.mataKuliah', 'kelas.ruangan'])
            ->select('pertemuans.*')
            ->orderBy('mata_kuliahs.kode_mk', 'asc')
            ->orderBy('pertemuans.pertemuan_ke', 'asc')
            ->get();

        if ($pertemuans->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data pertemuan tidak ditemukan',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $pertemuans
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'pertemuan_ke' => 'required|integer',
            'tanggal' => 'required|date',
            'topik' => 'required|string|max:255',
        ]);

        // optional: prevent duplicate pertemuan_ke dalam 1 kelas
        $exists = Pertemuan::where('kelas_id', $validated['kelas_id'])
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'errors' => 'Pertemuan ke ' . $validated['pertemuan_ke'] . ' sudah ada di kelas ini, tidak bisa ditambahkan'
            ], 409);
        }

        $pertemuan = Pertemuan::create([
            'kelas_id' => $validated['kelas_id'],
            'pertemuan_ke' => $validated['pertemuan_ke'],
            'tanggal' => $validated['tanggal'],
            'topik' => $validated['topik'],
            'status' => 'Terjadwal',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pertemuan berhasil dibuat',
            'data' => $pertemuan->load('kelas'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pertemuan = Pertemuan::with('kelas')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Pertemuan berhasil diambil',
            'data' => $pertemuan->load('kelas'),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pertemuan $pertemuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pertemuan = Pertemuan::with('kelas')->findOrFail($id);


        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'pertemuan_ke' => 'required|integer',
            'tanggal' => 'required|date',
            'topik' => 'required|string|max:255',
            'status' => 'required|string|in:Terjadwal,Berlangsung,Selesai',
            'started_at' => 'nullable|date_format:H:i|required_if:status,Berlangsung,Selesai',
            'ended_at' => 'nullable|date_format:H:i|after:started_at|required_if:status,Selesai',
        ]);

        if ($validated['status'] !== 'Selesai' && !empty($validated['ended_at'])) {
            return response()->json([
                'success' => false,
                'errors' => ['Waktu selesai hanya boleh diisi saat status Selesai']
            ], 422);
        }

        if (
            !empty($validated['ended_at']) &&
            !empty($validated['started_at']) &&
            $validated['ended_at'] < $validated['started_at']
        ) {
            return response()->json([
                'success' => false,
                'errors' => ['Waktu selesai tidak boleh lebih awal dari waktu mulai'],
            ], 422);
        }

        $pertemuan->update([
            'kelas_id' => $validated['kelas_id'],
            'pertemuan_ke' => $validated['pertemuan_ke'],
            'tanggal' => $validated['tanggal'],
            'topik' => $validated['topik'],
            'status' => $validated['status'],
            'started_at' => $validated['started_at'],
            'ended_at' => $validated['ended_at'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pertemuan berhasil diubah',
            'data' => $pertemuan->load('kelas'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pertemuan = Pertemuan::with('kelas')->findOrFail($id);

        if($pertemuan->status === 'Berlangsung') {
            return response()->json([
                'success' => false,
                'errors' => 'Pertemuan masih berlangsung, tidak bisa dihapus',
            ], 409);
        }

        if($pertemuan->status === 'Selesai') {
            return response()->json([
                'success' => false,
                'errors' => 'Pertemuan sudah selesai, tidak bisa dihapus',
            ], 409);
        }

        if($pertemuan->sesiAbsensi()->exists()) {
            return response()->json([
                'success' => false,
                'errors' => 'Pertemuan masih memiliki sesi absensi, tidak bisa dihapus',
            ], 409);
        }

        $pertemuan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pertemuan berhasil dihapus',
        ], 200);
    }

    // Ambil pertemuan berdasarkan kelas
    public function byKelas($kelas_id)
    {
        $data = Pertemuan::where('kelas_id', $kelas_id)
            ->orderBy('pertemuan_ke', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function start($id)
    {
        $pertemuan = Pertemuan::findOrFail($id);

        // ❗ prevent double start
        if ($pertemuan->started_at !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Pertemuan sudah dimulai'
            ], 409);
        }

        // ❗ pastikan tidak ada session aktif lain
        $active = Pertemuan::where('kelas_id', $pertemuan->kelas_id)
            ->where('status', 'Berlangsung')
            ->exists();

        if ($active) {
            return response()->json([
                'success' => false,
                'message' => 'Masih ada pertemuan yang berlangsung'
            ], 409);
        }

        $pertemuan->update([
            'status' => 'Berlangsung',
            'started_at' => now()->format('H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pertemuan dimulai',
            'data' => $pertemuan
        ]);
    }

    public function end($id)
    {
        $pertemuan = Pertemuan::findOrFail($id);

        if ($pertemuan->started_at === null) {
            return response()->json([
                'success' => false,
                'message' => 'Pertemuan belum dimulai'
            ], 409);
        }

        if ($pertemuan->ended_at !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Pertemuan sudah selesai'
            ], 409);
        }

        $pertemuan->update([
            'status' => 'Selesai',
            'ended_at' => now()->format('H:i:s'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pertemuan diakhiri',
            'data' => $pertemuan
        ]);
    }
}
