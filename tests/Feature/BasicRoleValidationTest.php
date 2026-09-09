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

test('admin can login and access admin dashboard', function () {
    $response = $this->post('/login', [
        'email' => $this->adminUser->email,
        'password' => 'password123',
    ]);

    $this->assertAuthenticated();
    // Admin is redirected to admin dashboard
    $response->assertRedirect('/admin/dashboard');

    // Test admin can access admin routes after login
    $adminResponse = $this->get('/admin/dashboard');
    $adminResponse->assertStatus(200);
});

test('barbero can login and access barbero dashboard', function () {
    $response = $this->post('/login', [
        'email' => $this->barberoUser->email,
        'password' => 'password123',
    ]);

    $this->assertAuthenticated();
    // Barbero is redirected to barbero dashboard
    $response->assertRedirect('/barbero/dashboard');

    // Test barbero can access barbero routes after login
    $barberoResponse = $this->get('/barbero/dashboard');
    $barberoResponse->assertStatus(200);
});

test('usuario can login and access user functions', function () {
    $response = $this->post('/login', [
        'email' => $this->usuarioUser->email,
        'password' => 'password123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/dashboard');

    // Test usuario can access user routes after login
    $citasResponse = $this->get('/citas/create');
    $citasResponse->assertStatus(200);
});

test('admin cannot access barbero dashboard', function () {
    $this->actingAs($this->adminUser);
    $response = $this->get('/barbero/dashboard');
    $response->assertStatus(403);
});

test('barbero cannot access admin dashboard', function () {
    $this->actingAs($this->barberoUser);
    $response = $this->get('/admin/dashboard');
    $response->assertStatus(403);
});

test('usuario cannot access admin dashboard', function () {
    $this->actingAs($this->usuarioUser);
    $response = $this->get('/admin/dashboard');
    $response->assertStatus(403);
});

test('usuario cannot access barbero dashboard', function () {
    $this->actingAs($this->usuarioUser);
    $response = $this->get('/barbero/dashboard');
    $response->assertStatus(403);
});
