<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ruangans = Ruangan::with('kelas')->orderBy('nama', 'asc')->get();

        if ($ruangans->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan tidak ditemukan',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil diambil',
            'data' => $ruangans
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
            'nama' => 'required|string|max:255,unique:ruangans,nama',
            'kapasitas' => 'required|integer|min:1',
        ]);

        $ruangan = Ruangan::create([
            'nama' => $validated['nama'],
            'kapasitas' => $validated['kapasitas'],
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil ditambahkan',
            'data' => $ruangan->load('kelas'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ruangan = Ruangan::with('kelas')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil ditemukan',
            'data' => $ruangan->load('kelas'),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ruangan $ruangan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::with('kelas')->findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255,unique:ruangans,nama,' . $ruangan->id,
            'kapasitas' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $ruangan->update([
            'nama' => $validated['nama'],
            'kapasitas' => $validated['kapasitas'],
            'is_active' => $validated['is_active'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil diubah',
            'data' => $ruangan->load('kelas'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ruangan = Ruangan::with('kelas')->findOrFail($id);

        if($ruangan->kelas()->exists()) {
            return response()->json([
                'success' => false,
                'errors' => 'Ruangan masih memiliki kelas, sehingga tidak bisa dihapus',
            ], 400);
        }

        $ruangan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ruangan berhasil dihapus',
        ], 200);
    }
}
