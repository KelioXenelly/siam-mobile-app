<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Prodi;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodis = Prodi::with('mahasiswas')->get();

        if ($prodis->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data Program Studi tidak ditemukan',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Program Studi berhasil diambil',
            'data' => $prodis
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
        $prodis = Prodi::select('kode_prodi')->get();

        $validated = $request->validate([
            'kode_prodi' => 'required|string|unique:prodis,kode_prodi',
            'nama_prodi' => 'required|string|max:255',
            'jenjang' => 'required|string|in:D3,D4,S1,S2,S3',
        ]);

        $prodi = Prodi::create([
            'kode_prodi' => $validated['kode_prodi'],
            'nama_prodi' => $validated['nama_prodi'],
            'jenjang' => $validated['jenjang'],
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Program Studi berhasil ditambahkan',
            'data' => $prodi
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $prodi = Prodi::with('mahasiswas')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Program Studi berhasil ditemukan',
            'data' => $prodi->load('mahasiswas'),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prodi $prodi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $prodi = Prodi::with('mahasiswas')->findOrFail($id);

        $validated = $request->validate([
            'kode_prodi' => 'required|string|unique:prodis,kode_prodi,' . $prodi->id,
            'nama_prodi' => 'required|string|max:255',
            'jenjang' => 'required|string|in:D3,D4,S1,S2,S3',
            'is_active' => 'required|boolean',
        ]);

        $prodi->update([
            'kode_prodi' => $validated['kode_prodi'],
            'nama_prodi' => $validated['nama_prodi'],
            'jenjang' => $validated['jenjang'],
            'is_active' => $validated['is_active'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Program Studi berhasil diubah',
            'data' => $prodi->load('mahasiswas'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $prodi = Prodi::findOrFail($id);

        if($prodi->mahasiswas()->exists()) {
            return response()->json([
                'success' => false,
                'errors' => 'Program Studi masih memiliki mahasiswa, tidak bisa dihapus',
            ], 400);
        }

        $prodi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Program Studi berhasil dihapus',
        ], 200);
    }
}
