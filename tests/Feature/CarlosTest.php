<?php
//pq no se ve alv
use App\Models\Cliente;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function crearCliente()
{
    $rol = Rol::create([
        'nombre' => 'Cliente',
        'descripcion' => 'Rol de cliente para pruebas',
    ]);

    $user = User::create([
        'email' => 'cliente@test.com',
        'password' => bcrypt('12345678'),
        'activo' => 1,
        'rol_id' => $rol->id,
    ]);

    $cliente = new Cliente();
    $cliente->nom = 'Juan';
    $cliente->apellido_p = 'Perez';
    $cliente->apellido_m = 'Lopez';
    $cliente->tel = '8123456789';
    $cliente->user_id = $user->id;
    $cliente->save();

    return [$user, $cliente];
}

test('ver perfil pide autenticacion', function () {
    $this->getJson('/api/ver-perfil')
        ->assertStatus(401);
});

test('cliente puede agarrar su perfil', function () {
    [$user, $cliente] = crearCliente();

    Sanctum::actingAs($user);

    $this->getJson('/api/ver-perfil')
        ->assertStatus(200);
});

test('editar perfil pide autenticacion', function () {
    $this->patchJson('/api/editar-perfil', [
        'nom' => 'Juan Actualizado',
    ])->assertStatus(401);
});

test('cliente puede editar su perfil', function () {
    [$user, $cliente] = crearCliente();

    Sanctum::actingAs($user);

    $this->patchJson('/api/editar-perfil', [
        'nom' => 'Juan Actualizado',
        'apellido_p' => 'Perez Actualizado',
        'apellido_m' => 'Lopez Actualizado',
        'email' => 'nuevo@test.com',
    ])->assertStatus(200);

$this->assertDatabaseHas('clientes', [
    'id' => $cliente->id,
    'nom' => 'Juan Actualizado',
    'apellido_p' => 'Perez Actualizado',
    'apellido_m' => 'Lopez Actualizado',
]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'nuevo@test.com',
    ]);
});

test('cliente no puede editar perfil con email repetido', function () {
    [$user, $cliente] = crearCliente();

    $rol = Rol::first();

    User::create([
        'email' => 'repetido@test.com',
        'password' => bcrypt('12345678'),
        'activo' => 1,
        'rol_id' => $rol->id,
    ]);

    Sanctum::actingAs($user);

    $this->patchJson('/api/editar-perfil', [
        'email' => 'repetido@test.com',
    ])->assertStatus(422)
      ->assertJsonValidationErrors(['email']);
});

test('mis citas pide autenticacion', function () {
    $this->getJson('/api/mis-citas')
        ->assertStatus(401);
});

test('cliente puede agarrar sus citas', function () {
    [$user, $cliente] = crearCliente();

    Sanctum::actingAs($user);

    $this->getJson('/api/mis-citas')
        ->assertStatus(200);
});