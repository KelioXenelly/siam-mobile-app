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
        $pertemuans = Pertemuan::with('kelas')->get();

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
        ]);

        // optional: prevent duplicate pertemuan_ke dalam 1 kelas
        $exists = Pertemuan::where('kelas_id', $validated['kelas_id'])
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Pertemuan ke sudah ada di kelas ini'
            ], 400);
        }

        $pertemuan = Pertemuan::create([
            'kelas_id' => $validated['kelas_id'],
            'pertemuan_ke' => $validated['pertemuan_ke'],
            'tanggal' => $validated['tanggal'],
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
            'kelas_id' => 'required|exists:kelas,id,' . $pertemuan->kelas->id,
            'pertemuan_ke' => 'required|integer',
            'tanggal' => 'required|date',
        ]);

        $pertemuan->update([
            'kelas_id' => $validated['kelas_id'],
            'pertemuan_ke' => $validated['pertemuan_ke'],
            'tanggal' => $validated['tanggal'],
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
}
