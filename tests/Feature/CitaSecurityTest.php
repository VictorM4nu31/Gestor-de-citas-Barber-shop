<?php

use App\Models\Barbero;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create([
        'email' => 'client@example.test',
    ]);

    $this->otherUser = User::factory()->create([
        'email' => 'other@example.test',
    ]);

    $this->barberoUser = User::factory()->create([
        'email' => 'barber@example.test',
    ]);

    $this->barbero = Barbero::create([
        'user_id' => $this->barberoUser->id,
        'nombre_completo' => 'Barbero Test',
        'email' => 'barber@example.test',
        'especialidad' => 'Fades',
        'experiencia' => '10 años',
        'activo' => true,
    ]);

    $this->servicio = Servicio::create([
        'nombre' => 'Corte clásico',
        'descripcion' => 'Corte clásico',
        'duracion' => 30,
        'precio' => 250,
        'publicado' => true,
        'orden' => 1,
    ]);

    $this->barbero->servicios()->attach($this->servicio);
});

test('a user cannot cancel another users appointment', function () {
    $cita = Cita::create([
        'nombre_completo' => $this->otherUser->name,
        'numero_telefono' => '5555555555',
        'correo_electronico' => $this->otherUser->email,
        'fecha' => now()->addDay()->toDateString(),
        'hora' => '10:00',
        'servicios' => (string) $this->servicio->id,
        'id_barbero' => $this->barbero->id,
        'id_usuario' => $this->otherUser->id,
        'costo' => $this->servicio->precio,
        'estado' => 'pendiente',
    ]);

    $response = $this->actingAs($this->user)->delete(route('citas.destroy', $cita));

    $response->assertNotFound();
    $this->assertModelExists($cita);
});

test('availability only exposes occupied hours', function () {
    Cita::create([
        'nombre_completo' => 'Cliente privado',
        'numero_telefono' => '5555555555',
        'correo_electronico' => 'private@example.test',
        'fecha' => now()->addDay()->toDateString(),
        'hora' => '10:00',
        'servicios' => (string) $this->servicio->id,
        'id_barbero' => $this->barbero->id,
        'id_usuario' => $this->otherUser->id,
        'costo' => $this->servicio->precio,
        'estado' => 'pendiente',
    ]);

    $response = $this->actingAs($this->user)->postJson(route('citas.check_availability'), [
        'barbero_id' => $this->barbero->id,
        'fecha' => now()->addDay()->toDateString(),
    ]);

    $response->assertOk()
        ->assertJson(['10:00:00'])
        ->assertJsonMissing(['Cliente privado', 'private@example.test']);
});

test('creating an appointment synchronizes its service pivot', function () {
    $response = $this->actingAs($this->user)->post(route('citas.store'), [
        'nombre_completo' => $this->user->name,
        'numero_telefono' => '5555555555',
        'correo_electronico' => $this->user->email,
        'servicios' => [$this->servicio->id],
        'id_barbero' => $this->barbero->id,
        'fecha' => now()->addDay()->toDateString(),
        'hora' => '11:00',
    ]);

    $response->assertRedirect(route('citas.index'));

    $cita = Cita::query()->latest('id')->firstOrFail();

    $this->assertDatabaseHas('cita_servicio', [
        'cita_id' => $cita->id,
        'servicio_id' => $this->servicio->id,
    ]);
});

test('a user can repeat one of their appointments without copying its date', function () {
    $cita = Cita::create([
        'nombre_completo' => $this->user->name,
        'numero_telefono' => '5555555555',
        'correo_electronico' => $this->user->email,
        'fecha' => now()->addDay()->toDateString(),
        'hora' => '11:00',
        'servicios' => (string) $this->servicio->id,
        'id_barbero' => $this->barbero->id,
        'id_usuario' => $this->user->id,
        'costo' => $this->servicio->precio,
        'estado' => 'pendiente',
    ]);
    $cita->serviciosMany()->sync([$this->servicio->id]);

    $response = $this->actingAs($this->user)->get(route('citas.repeat', $cita));

    $response->assertRedirect(route('citas.create', ['repeat' => $cita->id]));
});

test('a user dashboard shows the appointment summary', function () {
    $response = $this->actingAs($this->user)->get(route('dashboard'));

    $response->assertOk()->assertViewIs('dashboard')->assertViewHas('totalCitas', 0);
});
