<?php

use App\Http\Controllers\BarberoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
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

Route::get('/hola', function () {
    return view('holamundo');
});

Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');

/* Route::get('/citas/create', [CitaController::class, 'create'])->name('citas.create');
Route::post('/citas', [CitaController::class, 'store'])->name('citas.store'); */

require __DIR__.'/auth.php';


/*Rutas para acceder a las vistas de barberos y servicios*/
Route::resource('barberos', BarberoController::class);
Route::resource('servicios', ServicioController::class);
