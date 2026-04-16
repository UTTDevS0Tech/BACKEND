<?php

use App\Models\Rol;
use App\Models\User;
use App\Models\Cliente;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->rolRecepcionista = Rol::create([
        'nombre' => 'Recepcionista',
        'descripcion' => 'Rol para recepción',
    ]);

    $this->rolCliente = Rol::create([
        'nombre' => 'Cliente',
        'descripcion' => 'Rol cliente',
    ]);
});

function crearUsuario(int $rolId, string $email): User {
    return User::create([
        'email' => $email,
        'password' => Hash::make('password'),
        'activo' => true,
        'rol_id' => $rolId,
    ]);
}

it('recepcionista puede crear cliente', function () {
    $user = crearUsuario($this->rolRecepcionista->id, 'recep@test.com');
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/crear-cliente', [
        'nom' => 'Ana',
        'apellido_p' => 'Lopez',
        'apellido_m' => 'Martinez',
        'tel' => '9991234567',
    ]);

    $response->assertStatus(201);
});

it('no permite crear cliente sin autenticacion', function () {
    $response = $this->postJson('/api/crear-cliente', [
        'nom' => 'Ana',
        'apellido_p' => 'Lopez',
        'apellido_m' => 'Martinez',
        'tel' => '9991234567',
    ]);

    $response->assertStatus(401);
});


it('cliente no puede crear cliente (solo recepcionista)', function () {
    $user = crearUsuario($this->rolCliente->id, 'cliente@test.com');
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/crear-cliente', [
        'nom' => 'Pedro',
        'apellido_p' => 'Ramirez',
        'apellido_m' => 'Soto',
        'tel' => '9990001111',
    ]);

    $response->assertStatus(403);
});

it('recepcionista puede ver lista de clientes', function () {
    $user = crearUsuario($this->rolRecepcionista->id, 'recep2@test.com');
    Sanctum::actingAs($user);

    Cliente::create([
        'nom' => 'Carlos',
        'apellido_p' => 'Perez',
        'apellido_m' => 'Lopez',
        'tel' => '1234567890',
        'user_id' => null,
    ]);

    $response = $this->getJson('/api/ver-clientes');

    $response->assertStatus(200);
});

it('recepcionista puede buscar cliente por nombre', function () {
    $user = crearUsuario($this->rolRecepcionista->id, 'recep3@test.com');
    Sanctum::actingAs($user);

    Cliente::create([
        'nom' => 'Ana',
        'apellido_p' => 'Lopez',
        'apellido_m' => 'Martinez',
        'tel' => '1111111111',
        'user_id' => null,
    ]);

    Cliente::create([
        'nom' => 'Luis',
        'apellido_p' => 'Perez',
        'apellido_m' => 'Gomez',
        'tel' => '2222222222',
        'user_id' => null,
    ]);

    $response = $this->getJson('/api/ver-clientes?search=Ana');

    $response->assertStatus(200)
             ->assertJsonFragment(['nom' => 'Ana'])
             ->assertJsonMissing(['nom' => 'Luis']);
});


it('crear cliente falla si faltan datos', function () {
    $user = crearUsuario($this->rolRecepcionista->id, 'recep4@test.com');
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/crear-cliente', [
        'nom' => '',
    ]);

    $response->assertStatus(422);
});

it('endpoint ver clientes requiere autenticacion', function () {
    $response = $this->getJson('/api/ver-clientes');

    $response->assertStatus(401);
});