<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RencanaController;
use App\Http\Controllers\RoleDashboardController;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Rute Tamu (Login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Rute Utama Aplikasi (Wajib Login & Multi-Role)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [RoleDashboardController::class, 'index']);
    
    // Rute Dosen (FRK, FED, Riwayat)
    Route::get('/rencana', [RencanaController::class, 'index']);
    Route::get('/rencana/create', [RencanaController::class, 'create']);
    Route::post('/rencana', [RencanaController::class, 'store']);
    Route::get('/rencana/{id}/edit', [RencanaController::class, 'edit']);
    Route::post('/rencana/{id}/update', [RencanaController::class, 'update']);
    Route::get('/rencana/{id}/fed', [RencanaController::class, 'editFed']);
    Route::post('/rencana/{id}/fed', [RencanaController::class, 'updateFed']);
    Route::get('/rencana/riwayat', [RencanaController::class, 'riwayat']);
    Route::post('/rencana/{id}/delete', [RencanaController::class, 'destroy']);

    // Rute Asesor (Review, Setuju / Tolak dengan Komentar)
    Route::post('/rencana/{id}/status', [RencanaController::class, 'updateStatus']);

    // Rute Admin (Pengaturan Periode & Un-submit)
    Route::post('/admin/periode/update', [RoleDashboardController::class, 'updatePeriode']);
    Route::post('/rencana/{id}/unsubmit', [RencanaController::class, 'unsubmit']);
});