<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mataKuliahs = MataKuliah::with('kelas')->get();

        if ($mataKuliahs->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data mata kuliah tidak ditemukan',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data mata kuliah berhasil diambil',
            'data' => $mataKuliahs
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
            'kode_mk' => 'required|string|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
        ]);

        $mataKuliah = MataKuliah::create([
            'kode_mk' => $validated['kode_mk'],
            'nama_mk' => $validated['nama_mk'],
            'sks' => $validated['sks'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mata kuliah berhasil ditambahkan',
            'data' => $mataKuliah
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Mata kuliah ditemukan',
            'data' => $mataKuliah->load('kelas'),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataKuliah $mataKuliah)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);

        $validated = $request->validate([
            'kode_mk' => 'required|string|unique:mata_kuliahs,kode_mk,' . $mataKuliah->id,
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
        ]);

        $mataKuliah->update([
            'kode_mk' => $validated['kode_mk'],
            'nama_mk' => $validated['nama_mk'],
            'sks' => $validated['sks'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mata kuliah berhasil diubah',
            'data' => $mataKuliah->load('kelas'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);

        if($mataKuliah->kelas()->exists()) {
            return response()->json([
                'success' => false,
                'errors' => 'Mata kuliah masih memiliki kelas, sehingga tidak bisa dihapus',
            ], 400);
        }

        $mataKuliah->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mata kuliah berhasil dihapus',
        ], 200);
    }
}
