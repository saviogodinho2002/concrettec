<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\ConstructionController;


Route::redirect('/', '/dashboard');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de usuários
    Route::resource('users', UserController::class);

    // Rotas de empresas
    Route::resource('enterprises', EnterpriseController::class);

    // Rotas de obras
    Route::resource('constructions', ConstructionController::class);
});

require __DIR__.'/auth.php';
