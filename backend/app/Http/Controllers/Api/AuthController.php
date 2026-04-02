<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->identifier)
            ->orWhereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nim', $request->identifier);
            })
            ->orWhereHas('dosen', function ($q) use ($request) {
                $q->where('nidn', $request->identifier);
            })
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Login gagal'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Akun tidak aktif'
            ], 401);
        }

        $token = $user->createToken('siam_token')->plainTextToken;

        return response()->json([
            'message' => "Login berhasil! Selamat datang " . $user->name . " 👋",
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required'
        ]);
        $user = $request->user();

        if (!Hash::check($request->oldPassword, $user->password)) {
            return response()->json([
                'message' => 'Password lama salah'
            ], 400);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diubah'
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'nim' => 'required_if:role,mahasiswa|unique:mahasiswas,nim',
            'nidn' => 'required_if:role,dosen|unique:dosens,nidn',
            'prodi' => 'required_if:role,mahasiswa',
            'angkatan' => 'required_if:role,mahasiswa',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make("password"), // default password
            'role' => $request->role,
            'is_active' => true,
        ]);

        if ($request->role === 'mahasiswa') {
            $user->mahasiswa()->create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'angkatan' => $request->angkatan,
            ]);
        } elseif ($request->role === 'dosen') {
            $user->dosen()->create([
                'user_id' => $user->id,
                'nidn' => $request->nidn,
            ]);
        }

        return response()->json([
            'message' => 'Pengguna berhasil didaftarkan',
            'data' => $user->load('mahasiswa', 'dosen'),
        ], 201);
    }

    public function users()
    {
        $users = User::with(['mahasiswa', 'dosen'])->get();
        return response()->json([
            'message' => 'Daftar pengguna berhasil diambil',
            'data' => $users->load('mahasiswa', 'dosen'),
        ], 200);
    }

    public function updateUser(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'is_active' => 'sometimes|required|boolean',
            'prodi' => 'required_if:role,mahasiswa',
            'angkatan' => 'required_if:role,mahasiswa',
            'nidn' => 'required_if:role,dosen|unique:dosens,nidn,' . ($user->dosen ? $user->dosen->id : 'null'),
            'nim' => 'required_if:role,mahasiswa|unique:mahasiswas,nim,' . ($user->mahasiswa ? $user->mahasiswa->id : 'null'),
            'role' => 'sometimes|required|in:admin,dosen,mahasiswa',
            'password' => 'sometimes|nullable|min:8',
        ]);

        $userData = $request->only('name', 'email', 'is_active', 'role', 'password');

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        } else {
            unset($userData['password']); // Jangan update password jika tidak diisi
        }

        // Update tabel users
        $user->update($userData);

        if ($user->role === 'mahasiswa') {
            $user->dosen()->delete(); // Hapus data dosen jika sebelumnya adalah dosen

            $user->mahasiswa()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nim' => $request->nim,
                    'prodi' => $request->prodi,
                    'angkatan' => $request->angkatan,
                ]
            );
        } else if ($user->role === 'dosen') {
            $user->mahasiswa()->delete(); // Hapus data mahasiswa jika sebelumnya adalah mahasiswa

            $user->dosen()->updateOrCreate(
                ['user_id' => $user->id],
                ['nidn' => $request->nidn]
            );
        } else {
            // Kalau rolenya admin, hapus dua-duanya (karena admin gak butuh nim/nidn)
            $user->mahasiswa()->delete();
            $user->dosen()->delete();
        }

        return response()->json([
            'message' => 'Pengguna berhasil diperbarui',
            'data' => $user->load('mahasiswa', 'dosen'),
        ], 200);
    }

    public function deleteUser($user_id)
    {
        $user = User::findOrFail($user_id);
        
        if($user->mahasiswa) {
            $user->mahasiswa()->delete();
        } elseif($user->dosen) {
            $user->dosen()->delete();
        }
        
        $user->delete();

        return response()->json([
            'message' => 'Pengguna berhasil dihapus',
        ], 200);
    }
}