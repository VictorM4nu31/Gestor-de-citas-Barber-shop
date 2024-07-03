<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* Crear ruta para abrir el modulo de generador de citas */
Route::get('/appointment', function () {
    return view('appointment');
});

/* Crear ruta para poder abrir la página en la que se podrá visualizar el mapa*/
Route::get('/map', function () {
    return view('map');
});

require __DIR__.'/auth.php';
