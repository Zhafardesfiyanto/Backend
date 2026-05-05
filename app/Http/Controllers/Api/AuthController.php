<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    // Pastikan nama fungsinya persis 'syncUser' (huruf U besar)
    public function syncUser(Request $request)
    {
        $request->validate([
            'firebase_uid' => 'required|string',
            'name'         => 'required|string',
            'email'        => 'required|email',
            'role'         => 'required|in:student,teacher,admin'
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
            'data'    => $user
        ], 200);
    }
}