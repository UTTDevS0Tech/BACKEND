<?php

use App\Models\Cliente;
use App\Models\Cita;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function setupCliente(): array
{
    $rol = Rol::create([
        'nombre' => 'Cliente',
        'descripcion' => 'Rol cliente',
    ]);

    $user = User::create([
        'email' => 'test@cliente.com',
        'password' => bcrypt('password123'),
        'activo' => 1,
        'rol_id' => $rol->id,
    ]);

    $cliente = Cliente::create([
        'nom' => 'Carlos',
        'apellido_p' => 'Ramirez',
        'apellido_m' => 'Torres',
        'tel' => '8110001111',
        'user_id' => $user->id,
    ]);

    return [$user, $cliente];
}

test('ver perfil sin token', function () {
    $this->getJson('/api/ver-perfil')
        ->assertStatus(401);
});

test('ver perfil retorna nombre y apellido del cliente autenticado', function () {
    [$user, $cliente] = setupCliente();

    Sanctum::actingAs($user);

    $this->getJson('/api/ver-perfil')
        ->assertStatus(200)
        ->assertJsonFragment([
            'nom' => 'Carlos',
            'apellido_p' => 'Ramirez',
        ]);
});


test('editar perfil rechaza email con formato invalido', function () {
    [$user] = setupCliente();

    Sanctum::actingAs($user);

    $this->patchJson('/api/editar-perfil', [
        'email' => 'esto-no-es-un-email',
    ])->assertStatus(422)
      ->assertJsonValidationErrors(['email']);
});

test('mis citas sin token devuelve 401', function () {
    $this->getJson('/api/mis-citas')
        ->assertStatus(401);
});

test('mis citas regresa arreglo aunque este vacio', function () {
    [$user] = setupCliente();

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/mis-citas')
        ->assertStatus(200);

    $this->assertIsArray($response->json());
});

test('login falla con credenciales incorrectas', function () {
    setupCliente();

    $this->postJson('/api/login', [
        'email'    => 'test@cliente.com',
        'password' => 'wrongpassword',
    ])->assertStatus(401);
});

test('login falla con email inexistente', function () {
    setupCliente();

    $this->postJson('/api/login', [
        'email'    => 'noexiste@cliente.com',
        'password' => 'password123',
    ])->assertStatus(401);
});