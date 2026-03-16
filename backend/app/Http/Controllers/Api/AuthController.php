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

        $token = $user->createToken('siamas_token')->plainTextToken;

        return response()->json([
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
}