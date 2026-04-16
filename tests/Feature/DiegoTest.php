<?php

use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);


function loguearAdmin() {
    $rol = Rol::firstOrCreate(['nombre' => 'Administrador']);
    $user = User::create(['email' => 'admin_'.uniqid().'@t.com', 'password' => bcrypt('123'), 'activo' => true, 'rol_id' => $rol->id]);
    DB::table('personales')->insert(['nombre' => 'Admin Diego', 'user_id' => $user->id, 'created_at' => now(), 'updated_at' => now()]);
    Sanctum::actingAs($user);
    return $user;
}

function loguearCliente() {
    $rol = Rol::firstOrCreate(['nombre' => 'Cliente']);
    $user = User::create(['email' => 'cli_'.uniqid().'@t.com', 'password' => bcrypt('123'), 'activo' => true, 'rol_id' => $rol->id]);
    Sanctum::actingAs($user);
    return $user;
}


test('cliente puede ver su propio perfil', function () {
    loguearCliente();
    $this->getJson('/api/ver-perfil')->assertStatus(200);
});

test(' no puede ver perfil si no ha iniciado sesion', function () {
    $this->getJson('/api/ver-perfil')->assertStatus(401);
});

test('cliente puede editar sus datos basicos', function () {
    loguearCliente();
    $this->patchJson('/api/editar-perfil', ['email' => 'nuevo@test.com'])->assertStatus(200);
});

test(' cliente no puede usar un email que ya tiene otro usuario', function () {
   
    $rol = Rol::firstOrCreate(['nombre' => 'Cliente']);
    User::create(['email' => 'ocupado@test.com', 'password' => '123', 'rol_id' => $rol->id]);
    
    loguearCliente(); 
    $this->patchJson('/api/editar-perfil', ['email' => 'ocupado@test.com'])
         ->assertStatus(422); 
});

test('admin puede ver la lista de usuarios registrados', function () {
    loguearAdmin();
    $this->getJson('/api/users')->assertStatus(200);
});

test('admin puede ver detalle de un usuario', function () {
    $admin = loguearAdmin();
    $this->getJson("/api/users/{$admin->id}")->assertStatus(200);
});

test('admin puede crear un servicio', function () {
    loguearAdmin();
    $this->postJson('/api/servicios', [
        'nombre' => 'Corte Pantera',
        'descripcion' => 'El que pide el 5to DSM',
        'precio' => 200,
        'duracion' => 45
    ])->assertStatus(201);
});