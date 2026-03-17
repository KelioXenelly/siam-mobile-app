<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosens = Dosen::with('user')->get();

        if ($dosens->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data dosen tidak ditemukan',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil diambil',
            'data' => $dosens,
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
            'password' => 'required|min:6',
            'nidn' => 'required|unique:dosens,nidn',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'dosen',
        ]);

        $dosen = Dosen::create([
            'user_id' => $user->id,
            'nidn' => $validated['nidn'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil ditambahkan',
            'data' => $dosen->load('user'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dosen = Dosen::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil diambil',
            'data' => $dosen->load('user'),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dosen $dosen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $user = $dosen->user;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nidn' => 'required|unique:dosens,nidn,' . $dosen->id,
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $dosen->update([
            'nidn' => $validated['nidn'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil diubah',
            'data' => $dosen->load('user'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);

        $dosen->user()->delete();
        $dosen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data dosen berhasil dihapus',
        ]);
    }
}
