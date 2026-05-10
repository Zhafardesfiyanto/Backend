<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Sinkronisasi user dari Firebase ke database lokal.
     */
    public function syncUser(Request $request)
    {
        $request->validate([
            'firebase_uid' => 'required|string',
            'name'         => 'required|string',
            'email'        => 'required|email',
            'role'         => 'required|in:student,teacher,admin',
        ]);

        $user = User::updateOrCreate(
            ['firebase_uid' => $request->firebase_uid],
            [
                'name'  => $request->name,
                'email' => $request->email,
                'role'  => $request->role,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data user berhasil disinkronisasi dengan Laravel',
            'data'    => $user,
        ], 200);
    }

    /**
     * Update bio / profil user.
     */
    public function updateBio(Request $request)
    {
        $request->validate([
            'firebase_uid'    => 'required|string',
            'bio'             => 'nullable|string|max:500',
            'phone'           => 'nullable|string|max:20',
            'profile_picture' => 'nullable|string|url',
        ]);

        $user = User::where('firebase_uid', $request->firebase_uid)->firstOrFail();

        $user->update($request->only(['bio', 'phone', 'profile_picture']));

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data'    => $user,
        ], 200);
    }

    /**
     * Cari user berdasarkan nama atau email (untuk Service Center).
     */
    public function searchUser(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $users = User::where('name', 'like', '%' . $request->q . '%')
            ->orWhere('email', 'like', '%' . $request->q . '%')
            ->limit(20)
            ->get(['id', 'name', 'email', 'role', 'firebase_uid', 'created_at']);

        return response()->json([
            'success' => true,
            'data'    => $users,
        ], 200);
    }
}
