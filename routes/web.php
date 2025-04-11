<?php

use App\Http\Controllers\Dashboard;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\ConstructionController;
use App\Http\Controllers\CityController;


Route::redirect('/', '/dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard',[Dashboard::class,'index'])->name('dashboard');

    // Rotas de usuários
    Route::resource('users', UserController::class);

    // Rotas de empresas
    Route::resource('enterprises', EnterpriseController::class);

    // Rotas de obras
    Route::resource('constructions', ConstructionController::class);

    // Rota de busca de cidades
    Route::get('/cities/search', [CityController::class, 'search'])->name('cities.search');
});

require __DIR__.'/auth.php';
