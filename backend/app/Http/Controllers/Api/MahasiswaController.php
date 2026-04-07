<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::with('user', 'prodi')->get();

        if($mahasiswas->isEmpty()) {
            return response()->json([
                'success' => false,
                'errors' => 'Data mahasiswa tidak ditemukan',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil diambil',
            'data' => $mahasiswas,
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'nim' => 'required|string|max:255|unique:mahasiswas,nim',
            'prodi' => 'required|string|max:255',
            'angkatan' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
        ]);

        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'prodi' => $validated['prodi'],
            'angkatan' => $validated['angkatan'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data' => $mahasiswa->load('user'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil diambil',
            'data' => $mahasiswa->load('user'),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $user = $mahasiswa->user;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'. $user->id,
            'nim' => 'required|string|max:255|unique:mahasiswas,nim,' . $mahasiswa->id,
            'prodi' => 'required|string|max:255',
            'angkatan' => 'required|string|max:255',
        ]);
        
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $mahasiswa->update([
            'nim' => $validated['nim'],
            'prodi' => $validated['prodi'],
            'angkatan' => $validated['angkatan'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil diubah',
            'data' => $mahasiswa->load('user'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $mahasiswa->user()->delete();
        $mahasiswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil dihapus',
        ], 200);
    }
}
