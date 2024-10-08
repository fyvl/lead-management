<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\LeadController;

Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
Route::put('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');
Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
Route::delete('/leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
