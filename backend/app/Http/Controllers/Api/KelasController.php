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
        $kelas = Kelas::with(['mataKuliah', 'dosen', 'mahasiswas'])->get();

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
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'kode_kelas' => 'required|string',
            'semester' => 'required|integer',
            'tahun_ajaran' => 'required|string',
            'ruangan' => 'required|string',
        ]);

        $kelas = Kelas::create([
            'mata_kuliah_id' => $validated['mata_kuliah_id'],
            'dosen_id' => $validated['dosen_id'],
            'kode_kelas' => $validated['kode_kelas'],
            'semester' => $validated['semester'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'ruangan' => $validated['ruangan'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dibuat',
            'data' => $kelas->load(['mataKuliah', 'dosen', 'mahasiswas'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kelas = Kelas::with(['mataKuliah','dosen','mahasiswas'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diambil',
            'data' => $kelas->load(['mataKuliah','dosen','mahasiswas'])
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
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'kode_kelas' => 'required|string',
            'semester' => 'required|integer',
            'tahun_ajaran' => 'required|string',
            'ruangan' => 'required|string',
        ]);

        $kelas->update([
            'mata_kuliah_id' => $validated['mata_kuliah_id'],
            'dosen_id' => $validated['dosen_id'],
            'kode_kelas' => $validated['kode_kelas'],
            'semester' => $validated['semester'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'ruangan' => $validated['ruangan'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diupdate',
            'data' => $kelas->load(['mataKuliah','dosen','mahasiswas'])
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

    public function assignMahasiswa(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

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
