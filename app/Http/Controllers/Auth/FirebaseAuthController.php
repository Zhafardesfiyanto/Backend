<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Kreait\Firebase\Factory;
use Exception;

class FirebaseAuthController extends Controller
{
    protected $auth = null;

    public function __construct()
    {
        $credentialsPath = config('firebase.credentials');

        if ($credentialsPath && file_exists($credentialsPath)) {
            try {
                $this->auth = (new Factory)
                    ->withServiceAccount($credentialsPath)
                    ->createAuth();
            } catch (Exception $e) {
                report($e);
            }
        }
    }

    /**
     * Guard: pastikan Firebase Auth sudah terkonfigurasi.
     */
    private function firebaseReady(): bool
    {
        return $this->auth !== null;
    }

    /**
     * Verifikasi Firebase ID Token dari Flutter/client.
     * Flutter kirim: { "id_token": "..." }
     */
    public function verifyToken(Request $request): JsonResponse
    {
        if (!$this->firebaseReady()) {
            return response()->json([
                'success' => false,
                'message' => 'Firebase belum dikonfigurasi di server.',
            ], 503);
        }

        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            // Verifikasi token dengan Firebase Admin SDK
            $verifiedToken = $this->auth->verifyIdToken($request->id_token);
            $uid           = $verifiedToken->claims()->get('sub');

            // Cek apakah user sudah ada di DB lokal
            $user = User::where('firebase_uid', $uid)->first();

            if (!$user) {
                // Ambil data lengkap dari Firebase
                $firebaseUser = $this->auth->getUser($uid);

                $user = User::create([
                    'firebase_uid'    => $uid,
                    'name'            => $firebaseUser->displayName ?? ('User_' . substr($uid, 0, 8)),
                    'email'           => $firebaseUser->email ?? ($uid . '@firebase.local'),
                    'profile_picture' => $firebaseUser->photoUrl,
                    'role'            => 'student', // default role
                    'password'        => null,
                ]);
            }

            // Buat Sanctum token untuk request berikutnya
            $token = $user->createToken('firebase-auth')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'user'    => $user,
                'token'   => $token,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid: ' . $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Daftar user yang punya firebase_uid (artinya login via Google/Firebase).
     */
    public function getGoogleUsers(): JsonResponse
    {
        try {
            $users = User::whereNotNull('firebase_uid')
                ->select('id', 'name', 'email', 'role', 'profile_picture', 'firebase_uid', 'created_at')
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'total'   => $users->count(),
                'users'   => $users,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout — hapus semua Sanctum token milik user.
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Profil user yang sedang login (butuh Sanctum token).
     */
    public function getProfile(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user'    => $request->user(),
        ], 200);
    }
}
