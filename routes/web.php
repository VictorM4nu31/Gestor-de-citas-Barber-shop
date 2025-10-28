<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitaController;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\BarberoController;
use App\Http\Controllers\ServicioController;

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

require __DIR__.'/auth.php';

/*-------------------------rutas agregadas--------------------------------*/
Route::resource('barberos', BarberoController::class);
Route::resource('servicios', ServicioController::class);

// Rutas para las citas
Route::resource('citas', CitaController::class);

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


/* Route::resource('citas', CitaController::class); */
/* Route::get('citas/horarios', [CitaController::class, 'getAvailableTimes']); */

/* Route::get('/horas-disponibles', [CitaController::class, 'horasDisponibles']);

/*Manejar la solicitud AJAX de horas disponibles
Route::get('/citas/available-hours', [CitaController::class, 'availableHours'])->name('citas.availableHours'); */


/* Route::post('/citas/horas-disponibles', [CitaController::class, 'availableHours'])->name('citas.availableHours'); */
/* 

Route::get('/citas/disponibilidad', [CitaController::class, 'obtenerDisponibilidad'])->name('citas.disponibilidad'); */
// routes/web.php

Route::post('/citas/check-availability', [CitaController::class, 'checkAvailability'])->name('citas.check_availability');

Route::get('/', [BarberoController::class, 'welcome']);

// -------------- RUTAS PARA ADMIN (sólo usuarios con rol admin) --------------
use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/barberos', [AdminController::class, 'tableUsers'])->name('barberos.index');
    Route::get('/barberos/create', [AdminController::class, 'tableUsersCreate'])->name('barberos.create');
    Route::get('/barberos/{id}/edit', [AdminController::class, 'tableUsersEdit'])->name('barberos.edit');
    Route::get('/servicios', [AdminController::class, 'manageServices'])->name('servicios.index');
    Route::get('/servicios/create', [AdminController::class, 'servicesCreate'])->name('servicios.create');
    Route::get('/servicios/{id}/edit', [AdminController::class, 'servicesEdit'])->name('servicios.edit');
});

















