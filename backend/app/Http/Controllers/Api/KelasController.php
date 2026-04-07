<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::with(['mataKuliah', 'dosen', 'mahasiswas', 'ruangan'])->get();

        if($kelas->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data kelas berhasil diambil',
            'data' => $kelas,
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
            'kode_kelas' => 'required|string|unique:kelas,kode_kelas',
            'mata_kuliah_id' => 'required|integer|exists:mata_kuliahs,id',
            'dosen_id' => 'required|integer|exists:dosens,id',
            'ruangan_id' => 'required|integer|exists:ruangans,id',
            'semester' => 'required|integer|min:1|max:8',
            'tahun_ajaran' => 'required|string|max:255',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|time',
            'jam_selesai' => 'required|time',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $kelas = Kelas::create([
            'kode_kelas' => $validated['kode_kelas'],
            'mata_kuliah_id' => $validated['mata_kuliah_id'],
            'dosen_id' => $validated['dosen_id'],
            'ruangan_id' => $validated['ruangan_id'],
            'semester' => $validated['semester'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'hari' => $validated['hari'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'kapasitas' => $validated['kapasitas'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil ditambahkan',
            'data' => $kelas->load(['mataKuliah', 'dosen', 'mahasiswas', 'ruangan'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kelas = Kelas::with(['mataKuliah','dosen','mahasiswas', 'ruangan'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diambil',
            'data' => $kelas->load(['mataKuliah','dosen','mahasiswas', 'ruangan'])
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $validated = $request->validate([
            'kode_kelas' => 'required|string|unique:kelas,kode_kelas',
            'mata_kuliah_id' => 'required|integer|exists:mata_kuliahs,id',
            'dosen_id' => 'required|integer|exists:dosens,id',
            'ruangan_id' => 'required|integer|exists:ruangans,id',
            'semester' => 'required|integer|min:1|max:8',
            'tahun_ajaran' => 'required|string|max:255',
            'hari' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|time',
            'jam_selesai' => 'required|time',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $kelas->update([
            'kode_kelas' => $validated['kode_kelas'],
            'mata_kuliah_id' => $validated['mata_kuliah_id'],
            'dosen_id' => $validated['dosen_id'],
            'ruangan_id' => $validated['ruangan_id'],
            'semester' => $validated['semester'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'hari' => $validated['hari'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'kapasitas' => $validated['kapasitas'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diupdate',
            'data' => $kelas->load(['mataKuliah','dosen','mahasiswas', 'ruangan'])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus'
        ]);
    }

    public function assignMahasiswa(Request $request, $kelas_id)
    {
        $kelas = Kelas::findOrFail($kelas_id);

        $validated = $request->validate([
            'mahasiswa_ids' => 'required|array'
        ]);

        $kelas->mahasiswas()->sync($validated['mahasiswa_ids']);

        return response()->json([
            'success' => true,
            'message' => 'Mahasiswa berhasil ditambahkan ke kelas'
        ], 200);
    }

    public function kelasDosen(Request $request)
    {
        $dosen = $request->user()->dosen;

        $kelas = Kelas::where('dosen_id', $dosen->id)
            ->with('mataKuliah')
            ->get();
        
        if($kelas->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data kelas tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $kelas
        ], 200);
    }
}
