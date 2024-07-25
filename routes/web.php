<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarberoController;
use App\Http\Controllers\ServicioController;
use App\Models\Servicio;
use App\Models\Barbero;

Route::get('/', function () {
    return view('index');
});

// Ruta para el panel de control del administrador

Route::get('/admin/dashboard', [BarberoController::class, 'index'])->name('admin.dashboard');
// Rutas para la gestión de barberos
Route::resource('barberos', BarberoController::class);
// Ruta para crear barberos
Route::get('barberos/create', [BarberoController::class, 'create'])->name('admin.table-users-create');
// Ruta para volver a la lista de barberos
Route::get('/admin/table-users', [BarberoController::class, 'index'])->name('admin.table-users');

//Ruta para el panel de control del trabajador
Route::get('/worker/dashboard', function () {
    return view('worker.dashboard');
})->name('admin.dashboard');

Route::get('/dashboard', function () {
    $servicios = Servicio::all();
    $barberos = Barbero::all();

    return view('dashboard', compact('servicios', 'barberos'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::post('/citas/check-availability', [CitaController::class, 'checkAvailability'])->name('citas.check_availability');

// Rutas para las citas
Route::resource('citas', CitaController::class);