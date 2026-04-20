<?php

use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

function registrarRolesBase(): void
{
    Rol::insert([
        [
            'id' => 1,
            'nombre' => 'Estilista',
            'descripcion' => 'Rol de estilista',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'id' => 2,
            'nombre' => 'Cliente',
            'descripcion' => 'Rol de cliente',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'id' => 3,
            'nombre' => 'Recepcionista',
            'descripcion' => 'Rol de recepcionista',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);
}

function crearUsuarioConRol(int $rolId): User
{
    return User::create([
        'email' => 'user' . fake()->unique()->numberBetween(1, 99999) . '@test.com',
        'password' => bcrypt('password'),
        'activo' => 1,
        'rol_id' => $rolId,
    ]);
}

function crearUsuarioSinRol(): User
{
    return User::create([
        'email' => 'sinrol' . fake()->unique()->numberBetween(1, 99999) . '@test.com',
        'password' => bcrypt('password'),
        'activo' => 1,
        'rol_id' => null,
    ]);
}

function crearTablaTemporalVistaMisCitasEstilista(): void
{
    DB::statement('DROP TABLE IF EXISTS vista_mis_citas_estilista');

    DB::statement("
        CREATE TABLE vista_mis_citas_estilista (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            fecha_c DATE NOT NULL,
            hora_c TIME NOT NULL,
            cliente_nombre VARCHAR(255) NULL,
            servicio_nombre VARCHAR(255) NULL
        )
    ");
}

beforeEach(function () {
    registrarRolesBase();
});

test('el endpoint ver mis citas como estilista requiere autenticacion', function () {
    $this->getJson('/api/verMisCitasComoEstilista')
        ->assertStatus(401);
});

test('el endpoint mis citas requiere autenticacion', function () {
    $this->getJson('/api/mis-citas')
        ->assertStatus(401);
});

test('un usuario con rol cliente no puede acceder al endpoint ver mis citas como estilista', function () {
    $cliente = crearUsuarioConRol(2);

    Sanctum::actingAs($cliente);

    $this->getJson('/api/verMisCitasComoEstilista')
        ->assertStatus(403)
        ->assertJson([
            'message' => 'No tienes permisos para acceder a este recurso',
        ]);
});

test('un usuario con rol recepcionista no puede acceder al endpoint ver mis citas como estilista', function () {
    $recepcionista = crearUsuarioConRol(3);

    Sanctum::actingAs($recepcionista);

    $this->getJson('/api/verMisCitasComoEstilista')
        ->assertStatus(403)
        ->assertJson([
            'message' => 'No tienes permisos para acceder a este recurso',
        ]);
});

test('un estilista puede consultar sus citas aunque no existan registros', function () {
    crearTablaTemporalVistaMisCitasEstilista();

    $estilista = crearUsuarioConRol(1);

    Sanctum::actingAs($estilista);

    $this->getJson('/api/verMisCitasComoEstilista')
        ->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'error' => null,
            'data' => [],
            'message' => 'no se encontraron citas',
        ]);
});

test('un estilista solo puede visualizar las citas asociadas a su propio usuario', function () {
    crearTablaTemporalVistaMisCitasEstilista();

    $estilista = crearUsuarioConRol(1);
    $otroEstilista = crearUsuarioConRol(1);

    DB::table('vista_mis_citas_estilista')->insert([
        [
            'user_id' => $estilista->id,
            'fecha_c' => '2026-04-20',
            'hora_c' => '10:00:00',
            'cliente_nombre' => 'Ana',
            'servicio_nombre' => 'Corte',
        ],
        [
            'user_id' => $estilista->id,
            'fecha_c' => '2026-04-21',
            'hora_c' => '11:00:00',
            'cliente_nombre' => 'Luis',
            'servicio_nombre' => 'Tinte',
        ],
        [
            'user_id' => $otroEstilista->id,
            'fecha_c' => '2026-04-22',
            'hora_c' => '12:00:00',
            'cliente_nombre' => 'Pedro',
            'servicio_nombre' => 'Peinado',
        ],
    ]);

    Sanctum::actingAs($estilista);

    $response = $this->getJson('/api/verMisCitasComoEstilista');

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'success',
            'error' => null,
            'message' => 'CITASS',
        ])
        ->assertJsonCount(2, 'data');

    $data = $response->json('data');

    expect(collect($data)->pluck('cliente_nombre')->all())
        ->toBe(['Ana', 'Luis']);

    expect(collect($data)->pluck('user_id')->unique()->all())
        ->toBe([$estilista->id]);
});

test('las citas del estilista se devuelven ordenadas por fecha y hora de forma ascendente', function () {
    crearTablaTemporalVistaMisCitasEstilista();

    $estilista = crearUsuarioConRol(1);

    DB::table('vista_mis_citas_estilista')->insert([
        [
            'user_id' => $estilista->id,
            'fecha_c' => '2026-04-25',
            'hora_c' => '13:00:00',
            'cliente_nombre' => 'Carlos',
            'servicio_nombre' => 'Barba',
        ],
        [
            'user_id' => $estilista->id,
            'fecha_c' => '2026-04-20',
            'hora_c' => '15:00:00',
            'cliente_nombre' => 'Beto',
            'servicio_nombre' => 'Corte',
        ],
        [
            'user_id' => $estilista->id,
            'fecha_c' => '2026-04-20',
            'hora_c' => '09:00:00',
            'cliente_nombre' => 'Andrea',
            'servicio_nombre' => 'Peinado',
        ],
    ]);

    Sanctum::actingAs($estilista);

    $response = $this->getJson('/api/verMisCitasComoEstilista')
        ->assertStatus(200);

    $data = $response->json('data');

    expect($data[0]['cliente_nombre'])->toBe('Andrea');
    expect($data[1]['cliente_nombre'])->toBe('Beto');
    expect($data[2]['cliente_nombre'])->toBe('Carlos');
});

test('la respuesta de ver mis citas como estilista contiene la estructura JSON esperada cuando existen registros', function () {
    crearTablaTemporalVistaMisCitasEstilista();

    $estilista = crearUsuarioConRol(1);

    DB::table('vista_mis_citas_estilista')->insert([
        [
            'user_id' => $estilista->id,
            'fecha_c' => '2026-04-20',
            'hora_c' => '09:00:00',
            'cliente_nombre' => 'Andrea',
            'servicio_nombre' => 'Peinado',
        ],
    ]);

    Sanctum::actingAs($estilista);

    $this->getJson('/api/verMisCitasComoEstilista')
        ->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'error',
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'fecha_c',
                    'hora_c',
                    'cliente_nombre',
                    'servicio_nombre',
                ]
            ],
            'message',
        ]);
});