<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SuperAdminController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Hapus 'superadmin' sementara
Route::middleware(['auth'])->group(function () {
    // Hapus middleware ['auth', 'superadmin'] SEMENTARA
    Route::get('/hq-admin/dashboard', [SuperAdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/hq-admin/users', [SuperAdminController::class, 'users'])->name('admin.users');
    Route::get('/hq-admin/logs', [SuperAdminController::class, 'logs'])->name('admin.logs');
});
;
Route::middleware(['auth', 'superadmin'])->group(function () {
    // Halaman Utama Dashboard HQ
    Route::get('/hq-admin/dashboard', [SuperAdminController::class, 'index'])->name('admin.dashboard');

    // Halaman Khusus Management User
    Route::get('/hq-admin/users', [SuperAdminController::class, 'users'])->name('admin.users');

    // Halaman Khusus Audit Logs
    Route::get('/hq-admin/logs', [SuperAdminController::class, 'logs'])->name('admin.logs');

    // Halaman Service Center (Fitur Perbaikan Akun)
    Route::get('/hq-admin/service-center', [SuperAdminController::class, 'serviceCenter'])->name('admin.service');
});

require __DIR__ . '/auth.php';