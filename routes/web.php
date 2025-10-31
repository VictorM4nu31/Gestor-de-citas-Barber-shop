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
// Public barberos and servicios routes (accessible to all users)
Route::get('barberos', [BarberoController::class, 'index'])->name('public.barberos.index');
Route::get('barberos/{barbero}', [BarberoController::class, 'show'])->name('public.barberos.show');
Route::get('servicios', [ServicioController::class, 'index'])->name('public.servicios.index');
Route::get('servicios/{servicio}', [ServicioController::class, 'show'])->name('public.servicios.show');

Route::middleware('auth')->group(function () {
    // Rutas para las citas (user functionality)
    Route::resource('citas', CitaController::class);
    Route::get('/citas/servicios-barbero/{barbero}', [CitaController::class, 'getServiciosByBarbero'])->name('citas.servicios_barbero');
    Route::post('/citas/check-availability', [CitaController::class, 'checkAvailability'])->name('citas.check_availability');
});

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

Route::get('/', [BarberoController::class, 'welcome']);

// -------------- RUTAS PARA ADMIN (sólo usuarios con rol admin) --------------
use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Barberos CRUD (admin routes with admin. prefix)
    Route::get('/barberos', [AdminController::class, 'barberosIndex'])->name('barberos.index');
    Route::get('/barberos/create', [AdminController::class, 'barberosCreate'])->name('barberos.create');
    Route::post('/barberos', [AdminController::class, 'barberosStore'])->name('barberos.store');
    Route::get('/barberos/{barbero}', [AdminController::class, 'barberosShow'])->name('barberos.show');
    Route::get('/barberos/{barbero}/edit', [AdminController::class, 'barberosEdit'])->name('barberos.edit');
    Route::put('/barberos/{barbero}', [AdminController::class, 'barberosUpdate'])->name('barberos.update');
    Route::patch('/barberos/{barbero}/dar-de-baja', [AdminController::class, 'barberosDarDeBaja'])->name('barberos.dar_de_baja');
    Route::patch('/barberos/{barbero}/reactivar', [AdminController::class, 'barberosReactivar'])->name('barberos.reactivar');
    Route::delete('/barberos/{barbero}/eliminar-permanente', [AdminController::class, 'barberosEliminarPermanente'])->name('barberos.eliminar_permanente');
    Route::delete('/barberos/{barbero}', [AdminController::class, 'barberosDestroy'])->name('barberos.destroy');
    
    // Servicios CRUD
    Route::get('/servicios', [AdminController::class, 'serviciosIndex'])->name('servicios.index');
    Route::get('/servicios/create', [AdminController::class, 'serviciosCreate'])->name('servicios.create');
    Route::post('/servicios', [AdminController::class, 'serviciosStore'])->name('servicios.store');
    Route::get('/servicios/{servicio}', [AdminController::class, 'serviciosShow'])->name('servicios.show');
    Route::get('/servicios/{servicio}/edit', [AdminController::class, 'serviciosEdit'])->name('servicios.edit');
    Route::put('/servicios/{servicio}', [AdminController::class, 'serviciosUpdate'])->name('servicios.update');
    Route::delete('/servicios/{servicio}', [AdminController::class, 'serviciosDestroy'])->name('servicios.destroy');
    
    // Citas CRUD
    Route::get('/citas', [AdminController::class, 'citasIndex'])->name('citas.index');
    Route::get('/citas/create', [AdminController::class, 'citasCreate'])->name('citas.create');
    Route::post('/citas', [AdminController::class, 'citasStore'])->name('citas.store');
    Route::get('/citas/{cita}', [AdminController::class, 'citasShow'])->name('citas.show');
    Route::get('/citas/{cita}/edit', [AdminController::class, 'citasEdit'])->name('citas.edit');
    Route::put('/citas/{cita}', [AdminController::class, 'citasUpdate'])->name('citas.update');
    Route::delete('/citas/{cita}', [AdminController::class, 'citasDestroy'])->name('citas.destroy');
    Route::post('/citas/check-availability', [AdminController::class, 'citasCheckAvailability'])->name('citas.check_availability');
});

// -------------- RUTAS PARA BARBEROS (sólo usuarios con rol barbero) --------------
Route::middleware(['auth', 'role:barbero', 'active.barbero'])->prefix('barbero')->name('barbero.')->group(function () {
    Route::get('/dashboard', [BarberoController::class, 'dashboard'])->name('dashboard');
    Route::get('/citas', [BarberoController::class, 'citas'])->name('citas.index');
    Route::get('/citas/{cita}', [BarberoController::class, 'verCita'])->name('citas.show');
    Route::patch('/citas/{cita}/atender', [BarberoController::class, 'marcarAtendida'])->name('citas.atender');
});

















