<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RencanaController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\FollowUpController;

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

    // ================================
    // RENCANA / FRK / FED
    // ================================

    Route::get('/rencana', [RencanaController::class, 'index']);
    Route::get('/rencana/create', [RencanaController::class, 'create']);
    Route::post('/rencana', [RencanaController::class, 'store']);

    Route::get('/rencana/{id}/edit', [RencanaController::class, 'edit']);
    Route::post('/rencana/{id}/update', [RencanaController::class, 'update']);

    Route::get('/rencana/{id}/fed', [RencanaController::class, 'editFed']);
    Route::post('/rencana/{id}/fed', [RencanaController::class, 'updateFed']);

    Route::get('/rencana/riwayat', [RencanaController::class, 'riwayat']);

    Route::post('/rencana/{id}/delete', [RencanaController::class, 'destroy']);

    // Review status lama
    Route::post('/rencana/{id}/status', [
        RencanaController::class,
        'updateStatus'
    ]);

    // ================================
    // ADMIN
    // ================================

    Route::post('/admin/periode/update', [
        RoleDashboardController::class,
        'updatePeriode'
    ]);

    Route::post('/rencana/{id}/unsubmit', [
        RencanaController::class,
        'unsubmit'
    ]);

    // ================================
    // ASSESSMENT
    // ================================

    Route::get('/assessment', [
    AssessmentController::class,
    'index'
    ])->name('assessment.index');

    Route::get('/assessment/{id}/edit', [
        AssessmentController::class,
        'edit'
    ])->name('assessment.edit');

    Route::post('/assessment/{id}/review', [
        AssessmentController::class,
        'review'
    ])->name('assessment.review');

   // ================================
   // TINDAK LANJUT
   // ================================

   Route::get('/tindak-lanjut', [
        FollowUpController::class,
        'index'
    ])->name('tindak-lanjut.index');

    Route::post('/tindak-lanjut/{id}/submit', [
        FollowUpController::class,
        'submit'
    ])->name('tindak-lanjut.submit');

    Route::post('/tindak-lanjut/{id}/verify', [
        FollowUpController::class,
        'verify'
    ])->name('tindak-lanjut.verify');

    Route::post('/tindak-lanjut/{id}/close', [
        FollowUpController::class,
        'close'
    ])->name('tindak-lanjut.close');

});