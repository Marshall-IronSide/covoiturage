<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrajetController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('trajets.index');
});

// IMPORTANT: Define 'create' BEFORE the {trajet} parameter route
Route::middleware('auth')->group(function () {
    Route::get('/trajets/create', [TrajetController::class, 'create'])->name('trajets.create');
});

// Public routes for trajets
Route::get('/trajets', [TrajetController::class, 'index'])->name('trajets.index');
Route::get('/trajets/{trajet}', [TrajetController::class, 'show'])->name('trajets.show');

// Protected routes for trajets (store, edit, update, destroy)
Route::middleware('auth')->group(function () {
    Route::post('/trajets', [TrajetController::class, 'store'])->name('trajets.store');
    Route::get('/trajets/{trajet}/edit', [TrajetController::class, 'edit'])->name('trajets.edit');
    Route::patch('/trajets/{trajet}', [TrajetController::class, 'update'])->name('trajets.update');
    Route::delete('/trajets/{trajet}', [TrajetController::class, 'destroy'])->name('trajets.destroy');
    
    // Routes pour les réservations
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/trajets/{trajet}/reserver', [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/{reservation}/confirmer', [ReservationController::class, 'confirm'])->name('reservations.confirm');
    Route::post('/reservations/{reservation}/annuler', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';