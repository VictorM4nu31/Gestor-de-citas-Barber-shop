<?php

use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarberoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TranslationMetricsController;
use Illuminate\Support\Facades\Route;

// Language switching route
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('/', [BarberoController::class, 'welcome'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Public barberos and servicios routes (accessible to all users)
Route::get('barberos', [BarberoController::class, 'index'])->name('public.barberos.index');
Route::get('barberos/{barbero}', [BarberoController::class, 'show'])->name('public.barberos.show');
Route::get('servicios', [ServicioController::class, 'index'])->name('public.servicios.index');
Route::get('servicios/{servicio}', [ServicioController::class, 'show'])->name('public.servicios.show');

Route::middleware('auth')->group(function () {
    Route::post('/citas/available-slots', [CitaController::class, 'availableSlots'])->name('citas.available_slots');
    Route::get('/citas/{cita}/repeat', [CitaController::class, 'repeat'])->name('citas.repeat');
    Route::resource('citas', CitaController::class);
    Route::get('/citas/servicios-barbero/{barbero}', [CitaController::class, 'getServiciosByBarbero'])->name('citas.servicios_barbero');
    Route::post('/citas/check-availability', [CitaController::class, 'checkAvailability'])->name('citas.check_availability');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Translation metrics routes (admin only)
    Route::prefix('translation-metrics')->name('translation_metrics.')->group(function () {
        Route::get('/', [TranslationMetricsController::class, 'index'])->name('index');
        Route::get('/missing', [TranslationMetricsController::class, 'missing'])->name('missing');
        Route::delete('/clear', [TranslationMetricsController::class, 'clear'])->name('clear');
    });

    // Barberos CRUD
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

    // Gallery CRUD with security middleware
    Route::middleware(['gallery.security'])->group(function () {
        Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::get('/gallery/create', [GalleryController::class, 'create'])->name('gallery.create');
        Route::get('/gallery/{galleryImage}', [GalleryController::class, 'show'])->name('gallery.show');
        Route::get('/gallery/{galleryImage}/edit', [GalleryController::class, 'edit'])->name('gallery.edit');

        // Upload routes with rate limiting
        Route::middleware(['gallery.rate_limit'])->group(function () {
            Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
        });

        // Modification routes with CSRF protection (automatically applied by Laravel)
        Route::put('/gallery/{galleryImage}', [GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('/gallery/{galleryImage}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
        Route::post('/gallery/reorder', [GalleryController::class, 'reorder'])->name('gallery.reorder');
        Route::patch('/gallery/{galleryImage}/toggle-active', [GalleryController::class, 'toggleActive'])->name('gallery.toggle_active');
        Route::delete('/gallery/bulk-delete', [GalleryController::class, 'bulkDelete'])->name('gallery.bulk_delete');
    });
});

// Barbero routes
Route::middleware(['auth', 'role:barbero', 'active.barbero'])->prefix('barbero')->name('barbero.')->group(function () {
    Route::get('/dashboard', [BarberoController::class, 'dashboard'])->name('dashboard');
    Route::get('/citas', [BarberoController::class, 'citas'])->name('citas.index');
    Route::get('/citas/{cita}', [BarberoController::class, 'verCita'])->name('citas.show');
    Route::patch('/citas/{cita}/atender', [BarberoController::class, 'marcarAtendida'])->name('citas.atender');
});
