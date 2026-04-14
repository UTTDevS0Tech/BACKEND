<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EstilistaRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('roles')->insert([
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

    private function crearUsuario(int $rolId, string $email, bool $activo = true): User
    {
        $id = DB::table('users')->insertGetId([
            'email' => $email,
            'password' => bcrypt('password'),
            'activo' => $activo,
            'rol_id' => $rolId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }

    private function crearPersonal(int $userId, string $nombre = 'Estilista Demo', ?string $foto = null): int
    {
        return DB::table('personales')->insertGetId([
            'nombre' => $nombre,
            'foto' => $foto,
            'descripcion' => 'Perfil de prueba',
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_EstilistasDelIndexPublico(): void
    {
        $userEstilista = $this->crearUsuario(1, 'estilista@test.com');
        $userRecepcionista = $this->crearUsuario(3, 'recepcion@test.com');

        $this->crearPersonal($userEstilista->id, 'Carlos Estilista');
        $this->crearPersonal($userRecepcionista->id, 'Rocio Recepcionista');

        $response = $this->getJson('/api/estilistas');

        $response->assertStatus(200);

        $data = $response->json('data') ?? $response->json();

        $this->assertCount(1, $data);
        $this->assertEquals('Carlos Estilista', $data[0]['nombre']);
    }

    public function test_IndexEstilistas(): void
    {
        $userEstilista = $this->crearUsuario(1, 'dos@test.com');
        $this->crearPersonal($userEstilista->id, 'Andrea Estilista');

        $response = $this->getJson('/api/estilistas');

        $response->assertStatus(200);
    }

   public function test_IndexEstilistasMasEstructura(): void
{
    $userEstilista = $this->crearUsuario(1, 'tres@test.com');
    $this->crearPersonal($userEstilista->id, 'Mariana Estilista', 'foto.jpg');

    $response = $this->getJson('/api/estilistas');

    $response->assertStatus(200);

    $data = $response->json('data') ?? $response->json();

    $this->assertNotEmpty($data);
    $this->assertEquals('Mariana Estilista', $data[0]['nombre']);
    $this->assertArrayHasKey('id', $data[0]);
    $this->assertArrayHasKey('nombre', $data[0]);
}

    public function test_IndexVacio(): void
    {
        $response = $this->getJson('/api/estilistas');

        $response->assertStatus(200);

        $data = $response->json('data') ?? $response->json();

        $this->assertIsArray($data);
        $this->assertCount(0, $data);
    }

    public function test_EstilistaPorIdAutorizacion(): void
    {
        $userEstilista = $this->crearUsuario(1, 'uno@test.com');
        $personalId = $this->crearPersonal($userEstilista->id, 'Andrea Estilista');

        $response = $this->getJson("/api/estilistas/{$personalId}");

        $response->assertStatus(401);
    }

    public function test_EstilistaNoExisteAutorizacion(): void
    {
        $response = $this->getJson('/api/estilistas/999999');

        $response->assertStatus(401);
    }

    public function test_VerCitasAutorizacion(): void
    {
        $response = $this->getJson('/api/verMisCitasComoEstilista');

        $response->assertStatus(401);
    }

    public function test_UserNoEstilistaRuta(): void
    {
        $userRecepcionista = $this->crearUsuario(3, 'recepcionista@test.com');

        Sanctum::actingAs($userRecepcionista);

        $response = $this->getJson('/api/verMisCitasComoEstilista');

        $response->assertStatus(403);
    }
}