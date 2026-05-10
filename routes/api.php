<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\SupportTicketController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Auth\FirebaseAuthController;

// ─── PUBLIC ENDPOINTS ─────────────────────────────────────────────────────────
// Ping: test koneksi tanpa auth
Route::get('/ping', fn() => response()->json([
    'success' => true,
    'message' => 'Laravel server aktif ✅',
    'time'    => now()->toIso8601String(),
]));

// Debug: cek isi Firestore app_ratings via REST API (tanpa ext-grpc)
Route::get('/debug/firestore', function () {
    $credentialsPath = config('firebase.credentials');

    if (empty($credentialsPath) || !file_exists($credentialsPath)) {
        return response()->json(['error' => 'Credentials tidak ditemukan: ' . $credentialsPath]);
    }

    try {
        $keyData   = json_decode(file_get_contents($credentialsPath), true);
        $projectId = $keyData['project_id'];

        $credentials = new \Google\Auth\Credentials\ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/datastore'],
            $keyData
        );
        $token = $credentials->fetchAuthToken()['access_token'] ?? null;

        if (!$token) {
            return response()->json(['error' => 'Gagal mendapatkan access token']);
        }

        $url      = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/app_ratings";
        $response = (new \GuzzleHttp\Client())->get($url, [
            'headers' => ['Authorization' => "Bearer {$token}"],
        ]);

        $body = json_decode($response->getBody(), true);

        return response()->json([
            'project_id' => $projectId,
            'total_docs' => count($body['documents'] ?? []),
            'raw'        => $body,
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

// ─── FIREBASE AUTH ────────────────────────────────────────────────────────────
// Public: Flutter kirim ID token, dapat Sanctum token balik
Route::post('/auth/firebase-login', [FirebaseAuthController::class, 'verifyToken']);

// Protected: butuh Sanctum token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout',   [FirebaseAuthController::class, 'logout']);
    Route::get('/auth/profile',   [FirebaseAuthController::class, 'getProfile']);
    Route::get('/auth/google-users', [FirebaseAuthController::class, 'getGoogleUsers']);
});

// --- FOLDER: AUTHENTICATION ---
Route::post('/user/sync', [AuthController::class, 'syncUser']);
Route::put('/user/bio', [AuthController::class, 'updateBio']);

// --- FOLDER: CLASS MANAGEMENT ---
Route::post('/classes', [ClassController::class, 'createClass']);
Route::post('/classes/join', [ClassController::class, 'joinClass']);
Route::delete('/classes/kick', [ClassController::class, 'kickStudent']);

// --- FOLDER: EXAMS & QUIZZES ---
Route::post('/folders', [ExamController::class, 'createFolder']);
Route::post('/exams', [ExamController::class, 'createExam']);
Route::post('/exams/questions', [ExamController::class, 'addQuestion']);
Route::post('/exams/submit', [SubmissionController::class, 'submit']);

// --- FOLDER: ADMIN & SERVICE CENTER ---
Route::post('/exams/{examId}/scores/{submissionId}', [SubmissionController::class, 'grade']);

// --- ANTI CHEAT ---
Route::post('/exams/report-violation', [ExamController::class, 'reportViolation']);
Route::get('/exams/{examId}/violations', [ExamController::class, 'getViolations']);

// --- KHUSUS SERVICE CENTER / SUPER ADMIN ---
Route::prefix('service-center')->group(function () {
    Route::get('/users/search', [AuthController::class, 'searchUser']);
    Route::post('/exams/reset-session/{submissionId}', [SubmissionController::class, 'resetSession']);
});

// --- HQ ADMIN ---
Route::get('/hq-admin/dashboard', [SuperAdminController::class, 'index']);
Route::get('/hq-admin/users', [SuperAdminController::class, 'users']);
Route::delete('/hq-admin/users/{id}', [SuperAdminController::class, 'destroyUser']);
Route::post('/hq-admin/exams/reset', [SuperAdminController::class, 'emergencyReset']);

// --- CUSTOMER SERVICE / SUPPORT TICKETS ---
// User: kirim pesan & lihat riwayat tiket sendiri (butuh Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/support/send',      [SupportTicketController::class, 'send']);
    Route::get('/support/my-tickets', [SupportTicketController::class, 'myTickets']);
});

// Admin only: lihat semua tiket, balas, tutup tiket (butuh Sanctum token)
// TODO: tambah middleware 'role:superadmin' jika sudah ada role middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/support/all',            [SupportTicketController::class, 'all']);
    Route::put('/support/{id}/reply',     [SupportTicketController::class, 'reply']);
    Route::put('/support/{id}/close',     [SupportTicketController::class, 'close']);
});