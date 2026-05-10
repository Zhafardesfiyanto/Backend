<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Admin Routes (Super Admin only) ─────────────────────────────────────────
Route::middleware(['auth', 'superadmin'])->prefix('hq-admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [SuperAdminController::class, 'destroyUser'])->name('users.destroy');

    // Audit Logs
    Route::get('/logs', [SuperAdminController::class, 'logs'])->name('logs');

    // Customer Service / Support Tickets
    Route::get('/service-center', [SuperAdminController::class, 'serviceCenter'])->name('service');
    Route::patch('/service-center/{ticket}', [SuperAdminController::class, 'updateTicket'])->name('service.update');

    // Ratings & Firestore Introspection
    Route::get('/ratings', [SuperAdminController::class, 'ratings'])->name('ratings');
});

// ─── Auth Routes ──────────────────────────────────────────────────────────────
require __DIR__ . '/auth.php';
