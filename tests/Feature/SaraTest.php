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

function crearUsuarioConRolSara(int $rolId, string $email): User {
    return User::create([
        'email' => $email,
        'password' => Hash::make('password'),
        'activo' => true,
        'rol_id' => $rolId,
    ]);
}

it('permite a un recepcionista crear un cliente', function () {
    $recepcionista = crearUsuarioConRolSara(
        $this->rolRecepcionista->id,
        'recep@demo.com'
    );

    Sanctum::actingAs($recepcionista);

    $payload = [
        'nom' => 'Ana',
        'apellido_p' => 'Lopez',
        'apellido_m' => 'Martinez',
        'tel' => '9991234567',
    ];

    $response = $this->postJson('/api/crear-cliente', $payload);

    $response->assertStatus(201);

    $this->assertDatabaseHas('clientes', [
        'nom' => 'Ana',
        'apellido_p' => 'Lopez',
        'apellido_m' => 'Martinez',
        'tel' => '9991234567',
        'user_id' => null,
    ]);
});

it('impide que un usuario con otro rol cree clientes', function () {
    $clienteUser = crearUsuarioConRolSara(
        $this->rolCliente->id,
        'cliente@demo.com'
    );

    Sanctum::actingAs($clienteUser);

    $response = $this->postJson('/api/crear-cliente', [
        'nom' => 'Pedro',
        'apellido_p' => 'Ramirez',
        'apellido_m' => 'Soto',
        'tel' => '9990001111',
    ]);

    $response
        ->assertStatus(403)
        ->assertJson([
            'message' => 'No tienes permisos para acceder a este recurso',
        ]);
});

it('permite a un recepcionista buscar clientes por nombre', function () {
    $recepcionista = crearUsuarioConRolSara(
        $this->rolRecepcionista->id,
        'recep2@demo.com'
    );

    Sanctum::actingAs($recepcionista);

    Cliente::create([
        'nom' => 'Ana',
        'apellido_p' => 'Lopez',
        'apellido_m' => 'Martinez',
        'tel' => '1111111111',
        'user_id' => null,
    ]);

    Cliente::create([
        'nom' => 'Carlos',
        'apellido_p' => 'Perez',
        'apellido_m' => 'Gomez',
        'tel' => '2222222222',
        'user_id' => null,
    ]);

    $response = $this->getJson('/api/ver-clientes?search=Ana');

    $response->assertStatus(200);
    $response->assertJsonFragment([
        'nom' => 'Ana',
        'apellido_p' => 'Lopez',
    ]);
    $response->assertJsonMissing([
        'nom' => 'Carlos',
        'apellido_p' => 'Perez',
    ]);
});