<?php

use App\Models\Barbero;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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

describe('Admin Role Access', function () {
    test('admin can access admin dashboard', function () {
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    });

    test('admin can access barberos management', function () {
        $response = $this->actingAs($this->adminUser)->get('/admin/barberos');
        $response->assertStatus(200);
        $response->assertViewIs('admin.barberos.index');
    });

    test('admin can access servicios management', function () {
        $response = $this->actingAs($this->adminUser)->get('/admin/servicios');
        $response->assertStatus(200);
        $response->assertViewIs('admin.servicios.index');
    });

    test('admin can access citas management', function () {
        $response = $this->actingAs($this->adminUser)->get('/admin/citas');
        $response->assertStatus(200);
        $response->assertViewIs('admin.citas.index');
    });

    test('admin can create new barbero', function () {
        $response = $this->actingAs($this->adminUser)->get('/admin/barberos/create');
        $response->assertStatus(200);
        $response->assertViewIs('admin.barberos.create');
    });

    test('admin can create new servicio', function () {
        $response = $this->actingAs($this->adminUser)->get('/admin/servicios/create');
        $response->assertStatus(200);
        $response->assertViewIs('admin.servicios.create');
    });
});

describe('Barbero Role Access', function () {
    test('barbero can access barbero dashboard', function () {
        $response = $this->actingAs($this->barberoUser)->get('/barbero/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('barbero.dashboard');
    });

    test('barbero cannot access admin dashboard', function () {
        $response = $this->actingAs($this->barberoUser)->get('/admin/dashboard');
        $response->assertStatus(403);
    });

    test('barbero cannot access admin barberos management', function () {
        $response = $this->actingAs($this->barberoUser)->get('/admin/barberos');
        $response->assertStatus(403);
    });

    test('barbero cannot access admin servicios management', function () {
        $response = $this->actingAs($this->barberoUser)->get('/admin/servicios');
        $response->assertStatus(403);
    });
});

describe('Usuario Role Access', function () {
    test('usuario can access citas creation', function () {
        $response = $this->actingAs($this->usuarioUser)->get('/citas/create');
        $response->assertStatus(200);
        $response->assertViewIs('usuario.citas.create');
    });

    test('usuario can view their citas', function () {
        $response = $this->actingAs($this->usuarioUser)->get('/citas');
        $response->assertStatus(200);
        $response->assertViewIs('usuario.citas.index');
    });

    test('usuario cannot access admin dashboard', function () {
        $response = $this->actingAs($this->usuarioUser)->get('/admin/dashboard');
        $response->assertStatus(403);
    });

    test('usuario cannot access barbero dashboard', function () {
        $response = $this->actingAs($this->usuarioUser)->get('/barbero/dashboard');
        $response->assertStatus(403);
    });

    test('usuario can view public barberos list', function () {
        $response = $this->actingAs($this->usuarioUser)->get('/barberos');
        $response->assertStatus(200);
    });

    test('usuario can view public servicios list', function () {
        $response = $this->actingAs($this->usuarioUser)->get('/servicios');
        $response->assertStatus(200);
    });
});

describe('Authentication and Login Flow', function () {
    test('admin can login and be redirected to admin dashboard', function () {
        $response = $this->post('/login', [
            'email' => $this->adminUser->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
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
});

describe('Functional Testing by Role', function () {
    test('admin can create and manage barberos', function () {
        $barberoData = [
            'nombre_completo' => 'Nuevo Barbero Test',
            'email' => 'nuevobarbero@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'telefono' => '9876543210',
            'especialidad' => 'Corte moderno',
            'experiencia' => '3 años',
        ];

        $response = $this->actingAs($this->adminUser)
            ->post('/admin/barberos', $barberoData);

        $response->assertRedirect('/admin/barberos');
        $this->assertDatabaseHas('barberos', [
            'nombre_completo' => 'Nuevo Barbero Test',
            'email' => 'nuevobarbero@test.com',
        ]);
    });

    test('admin can create and manage servicios', function () {
        $servicioData = [
            'nombre' => 'Nuevo Servicio Test',
            'descripcion' => 'Descripción del nuevo servicio',
            'duracion' => 45,
            'precio' => 35.00,
            'publicado' => true,
            'orden' => 2,
        ];

        $response = $this->actingAs($this->adminUser)
            ->post('/admin/servicios', $servicioData);

        $response->assertRedirect('/admin/servicios');
        $this->assertDatabaseHas('servicios', [
            'nombre' => 'Nuevo Servicio Test',
            'precio' => 35.00,
        ]);
    });

    test('usuario can create citas', function () {
        $citaData = [
            'nombre_completo' => 'Cliente Test',
            'numero_telefono' => '1234567890',
            'correo_electronico' => 'cliente@test.com',
            'servicios' => [$this->testServicio->id],
            'id_barbero' => $this->testBarbero->id,
            'fecha' => now()->addDays(1)->format('Y-m-d'),
            'hora' => '10:00',
        ];

        $response = $this->actingAs($this->usuarioUser)
            ->post('/citas', $citaData);

        $response->assertRedirect('/citas');
        $this->assertDatabaseHas('citas', [
            'nombre_completo' => 'Cliente Test',
            'id_usuario' => $this->usuarioUser->id,
        ]);
    });
});
