<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('agarrar todos los servicios', function () {
    $this->getJson('/api/servicios')
        ->assertStatus(200);
});

test('agarrar todos los tipos de servicio', function () {
    $this->getJson('/api/tipo-servicios')
        ->assertStatus(200);
});

test('la galeria pide autenticacion', function () {
    $this->getJson('/api/galeria')
        ->assertStatus(401);
});

test('agarrar la galeria publica', function () {
    $this->getJson('/api/galeria/publica')
        ->assertStatus(200);
});

test('agarrar categorias de galeria', function () {
    $this->getJson('/api/categorias-galeria')
        ->assertStatus(200);
});

test('agarrar estilistas', function () {
    $this->getJson('/api/estilistas')
        ->assertStatus(200);
});

test('disponibilidad de citas valida campos requeridos', function () {
    $this->getJson('/api/citas/disponibilidad')
        ->assertStatus(422)
        ->assertJsonValidationErrors(['personal_id', 'fecha']);
});