<?php

use App\Models\Barbero;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->barberoUser = User::factory()->create();
    $this->barbero = Barbero::create([
        'user_id' => $this->barberoUser->id,
        'nombre_completo' => 'Barbero Test',
        'email' => 'availability-barber@example.test',
        'especialidad' => 'Fades',
        'experiencia' => '10 años',
        'activo' => true,
    ]);

    $this->service = Servicio::create([
        'nombre' => 'Corte',
        'descripcion' => 'Corte',
        'duracion' => 30,
        'precio' => 200,
        'publicado' => true,
        'orden' => 1,
    ]);

    $this->barbero->servicios()->attach($this->service);
});

test('returns half-hour slots inside the configured opening hours', function () {
    $response = $this->actingAs($this->user)->postJson(route('citas.available_slots'), [
        'barbero_id' => $this->barbero->id,
        'fecha' => now()->addDay()->toDateString(),
        'servicios' => [$this->service->id],
    ]);

    $response->assertOk()
        ->assertJsonPath('duration', 30)
        ->assertJsonPath('slots.0.value', '09:00')
        ->assertJsonPath('slots.21.value', '19:30');
});

test('excludes slots that overlap an existing appointment duration', function () {
    $cita = Cita::create([
        'nombre_completo' => 'Cliente',
        'numero_telefono' => '5555555555',
        'correo_electronico' => 'client@example.test',
        'fecha' => now()->addDay()->toDateString(),
        'hora' => '10:00',
        'servicios' => (string) $this->service->id,
        'id_barbero' => $this->barbero->id,
        'id_usuario' => $this->user->id,
        'costo' => $this->service->precio,
        'estado' => 'pendiente',
    ]);
    $cita->serviciosMany()->sync([$this->service->id]);

    $response = $this->actingAs($this->user)->postJson(route('citas.available_slots'), [
        'barbero_id' => $this->barbero->id,
        'fecha' => now()->addDay()->toDateString(),
        'servicios' => [$this->service->id],
    ]);

    $response->assertOk()
        ->assertJsonMissing(['value' => '10:00'])
        ->assertJsonPath('slots.1.value', '09:30')
        ->assertJsonPath('slots.2.value', '10:30');
});

test('rejects a service that is not assigned to the selected barber', function () {
    $unassignedService = Servicio::create([
        'nombre' => 'Barba',
        'descripcion' => 'Barba',
        'duracion' => 30,
        'precio' => 150,
        'publicado' => true,
        'orden' => 2,
    ]);

    $response = $this->actingAs($this->user)->postJson(route('citas.available_slots'), [
        'barbero_id' => $this->barbero->id,
        'fecha' => now()->addDay()->toDateString(),
        'servicios' => [$unassignedService->id],
    ]);

    $response->assertUnprocessable()->assertJsonValidationErrors('servicios');
});
