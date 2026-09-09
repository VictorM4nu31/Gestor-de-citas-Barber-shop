<?php

use App\Models\Barbero;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'barbero']);
    Role::firstOrCreate(['name' => 'usuario']);

    // Create test users
    $this->adminUser = User::factory()->create([
        'name' => 'Test Admin',
        'email' => 'testadmin@test.com',
        'password' => Hash::make('password123'),
    ]);
    $this->adminUser->assignRole('admin');

    $this->barberoUser = User::factory()->create([
        'name' => 'Test Barbero',
        'email' => 'testbarbero@test.com',
        'password' => Hash::make('password123'),
    ]);
    $this->barberoUser->assignRole('barbero');

    $this->usuarioUser = User::factory()->create([
        'name' => 'Test Usuario',
        'email' => 'testusuario@test.com',
        'password' => Hash::make('password123'),
    ]);
    $this->usuarioUser->assignRole('usuario');

    // Create test barbero record for barbero user
    $this->testBarbero = Barbero::create([
        'nombre_completo' => 'Test Barbero',
        'email' => 'testbarbero@test.com',
        'password' => Hash::make('password123'),
        'user_id' => $this->barberoUser->id,
        'telefono' => '1234567890',
        'especialidad' => 'Corte clásico',
        'experiencia' => '5 años de experiencia',
    ]);

    // Create test services
    $this->testServicio = Servicio::create([
        'nombre' => 'Corte de cabello',
        'descripcion' => 'Corte de cabello profesional',
        'duracion' => 30,
        'precio' => 25.00,
        'publicado' => true,
        'orden' => 1,
    ]);
    $this->testBarbero->servicios()->attach($this->testServicio);
});

describe('Admin Functionality Validation', function () {
    test('admin can access all admin views', function () {
        $this->actingAs($this->adminUser);

        // Dashboard
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');

        // Barberos management
        $response = $this->get('/admin/barberos');
        $response->assertStatus(200);
        $response->assertViewIs('admin.barberos.index');

        $response = $this->get('/admin/barberos/create');
        $response->assertStatus(200);
        $response->assertViewIs('admin.barberos.create');

        // Servicios management
        $response = $this->get('/admin/servicios');
        $response->assertStatus(200);
        $response->assertViewIs('admin.servicios.index');

        $response = $this->get('/admin/servicios/create');
        $response->assertStatus(200);
        $response->assertViewIs('admin.servicios.create');

        // Citas management
        $response = $this->get('/admin/citas');
        $response->assertStatus(200);
        $response->assertViewIs('admin.citas.index');
    });

    test('admin can create barberos', function () {
        $this->actingAs($this->adminUser);

        $barberoData = [
            'nombre_completo' => 'Nuevo Barbero Test',
            'email' => 'nuevobarbero@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefono' => '9876543210',
            'especialidad' => 'Corte moderno',
            'experiencia' => '3 años',
        ];

        $response = $this->post('/admin/barberos', $barberoData);
        $response->assertRedirect('/admin/barberos');

        $this->assertDatabaseHas('barberos', [
            'nombre_completo' => 'Nuevo Barbero Test',
            'email' => 'nuevobarbero@test.com',
        ]);
    });

    test('admin can create servicios', function () {
        $this->actingAs($this->adminUser);

        $servicioData = [
            'nombre' => 'Nuevo Servicio Test',
            'descripcion' => 'Descripción del nuevo servicio',
            'duracion' => 45,
            'precio' => 35.00,
            'publicado' => true,
            'orden' => 2,
        ];

        $response = $this->post('/admin/servicios', $servicioData);
        $response->assertRedirect('/admin/servicios');

        $this->assertDatabaseHas('servicios', [
            'nombre' => 'Nuevo Servicio Test',
            'precio' => 35.00,
        ]);
    });
});

describe('Barbero Functionality Validation', function () {
    test('barbero can access barbero dashboard', function () {
        $this->actingAs($this->barberoUser);

        $response = $this->get('/barbero/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('barbero.dashboard');
    });

    test('barbero cannot access admin functions', function () {
        $this->actingAs($this->barberoUser);

        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);

        $response = $this->get('/admin/barberos');
        $response->assertStatus(403);

        $response = $this->get('/admin/servicios');
        $response->assertStatus(403);
    });
});

describe('Usuario Functionality Validation', function () {
    test('usuario can access citas functionality', function () {
        $this->actingAs($this->usuarioUser);

        // Can access citas creation
        $response = $this->get('/citas/create');
        $response->assertStatus(200);
        $response->assertViewIs('usuario.citas.create');

        // Can view their citas
        $response = $this->get('/citas');
        $response->assertStatus(200);
        $response->assertViewIs('usuario.citas.index');
    });

    test('usuario can view public barberos and servicios', function () {
        $this->actingAs($this->usuarioUser);

        $response = $this->get('/barberos');
        $response->assertStatus(200);

        $response = $this->get('/servicios');
        $response->assertStatus(200);
    });

    test('usuario cannot access admin or barbero functions', function () {
        $this->actingAs($this->usuarioUser);

        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);

        $response = $this->get('/barbero/dashboard');
        $response->assertStatus(403);
    });
});

describe('View Structure Validation', function () {
    test('all restructured views exist and are accessible', function () {
        // Admin views
        $this->actingAs($this->adminUser);

        $adminViews = [
            '/admin/dashboard' => 'admin.dashboard',
            '/admin/barberos' => 'admin.barberos.index',
            '/admin/servicios' => 'admin.servicios.index',
            '/admin/citas' => 'admin.citas.index',
        ];

        foreach ($adminViews as $route => $view) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertViewIs($view);
        }

        // Barbero views
        $this->actingAs($this->barberoUser);

        $response = $this->get('/barbero/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('barbero.dashboard');

        // Usuario views
        $this->actingAs($this->usuarioUser);

        $usuarioViews = [
            '/citas/create' => 'usuario.citas.create',
            '/citas' => 'usuario.citas.index',
        ];

        foreach ($usuarioViews as $route => $view) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertViewIs($view);
        }
    });
});
