<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import semua Controller yang sudah kita buat
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\SubmissionController;



// --- FOLDER: AUTHENTICATION ---
Route::post('/user/sync', [AuthController::class, 'syncFirebase']);
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

// --- FOLDER: ADMIN & SERVICE CENTER DLL ---
Route::post('/exams/{examId}/scores/{submissionId}', [SubmissionController::class, 'grade']); 

//--- ANTI CHEAT NI BOS --- 
Route::post('/exams/report-violation', [ExamController::class, 'reportViolation']);
Route::get('/exams/{examId}/violations', [ExamController::class, 'getViolations']);





//-----------------------------ADMIN MODE GOD -----------------------------



// --- KHUSUS SERVICE CENTER / SUPER ADMIN ---
Route::prefix('service-center')->group(function () {
    // Cari user berdasarkan email/nama untuk troubleshoot
    Route::get('/users/search', [AuthController::class, 'searchUser']);
    
    // Paksa reset status ujian siswa (jika error teknis)
    Route::post('/exams/reset-session/{submissionId}', [SubmissionController::class, 'resetSession']);
    
    // Broadcast pesan ke semua user (Maintenance info, dll)
    Route::post('/broadcast', [NotificationController::class, 'sendGlobal']);
});

Route::get('/hq-admin/dashboard', [SuperAdminController::class, 'index']);
Route::get('/hq-admin/users', [SuperAdminController::class, 'index']);
Route::post('/hq-admin/users/{id}', [SuperAdminController::class, 'destroy']);
Route::post('/hq-admin/exams/reset', [SuperAdminController::class, 'emergencyReset']);