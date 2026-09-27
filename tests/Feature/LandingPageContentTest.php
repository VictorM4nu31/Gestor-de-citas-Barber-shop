<?php

use App\Models\Barbero;
use App\Models\GalleryImage;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin']);

    $this->admin = User::factory()->create([
        'name' => 'Test Admin',
        'email' => 'landing-admin@test.com',
        'password' => Hash::make('password123'),
    ]);
    $this->admin->assignRole('admin');
});

function crearImagenGaleria(): GalleryImage
{
    static $contador = 0;
    $contador++;

    return GalleryImage::create([
        'filename' => "galeria-{$contador}.jpg",
        'original_name' => "original-{$contador}.jpg",
        'path' => "gallery/galeria-{$contador}.jpg",
        'thumbnail_path' => "gallery/thumbnails/galeria-{$contador}_thumb.jpg",
        'size' => 1024,
        'mime_type' => 'image/jpeg',
        'display_order' => $contador,
        'is_active' => true,
    ]);
}

it('hides the gallery nav link when there are no active gallery images', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertDontSee(route('home').'#gallery', false);
    $response->assertDontSee('id="gallery"', false);
});

it('shows the gallery nav link when active gallery images exist', function () {
    crearImagenGaleria();

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee(route('home').'#gallery', false);
    $response->assertSee('id="gallery"', false);
});

it('renders the barber experience verbatim without appending a unit', function () {
    $barbero = Barbero::create([
        'user_id' => $this->admin->id,
        'nombre_completo' => 'Test Barbero',
        'email' => 'barbero-experiencia@test.com',
        'password' => Hash::make('password123'),
        'telefono' => '1234567890',
        'especialidad' => 'Corte clásico',
        'experiencia' => '10 años de experiencia en corte de cabello y estilizado.',
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.barberos.index'));

    $response->assertOk();
    $response->assertSee($barbero->experiencia, false);
    $response->assertDontSee($barbero->experiencia.' años', false);
});

it('keeps the services section copy separate from the field label', function () {
    expect(__('services.description'))->not->toBe('Descripción')
        ->and(__('services.description'))->toContain('servicios')
        ->and(__('services.description_label'))->toBe('Descripción');
});

it('labels the description field on the service detail page', function () {
    $servicio = Servicio::create([
        'nombre' => 'Corte de cabello',
        'descripcion' => 'Corte de cabello con asesoría de estilo.',
        'precio' => 280,
        'duracion' => 45,
        'activo' => true,
        'publicado' => true,
    ]);

    $response = $this->get(route('public.servicios.show', $servicio));

    $response->assertOk();
    $response->assertSee('Descripción:', false);
});
