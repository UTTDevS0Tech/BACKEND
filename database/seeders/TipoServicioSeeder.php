<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoServicioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipo_servicios')->insert([

            [
                'nombre' => 'Corte de dama',
                'precio' => 150.00,
                'descripcion' => 'Corte básico y estilizado',
                'activo' => true,
                'imagen' => null,
                'tiempo_estimado' => 30,
                'servicio_id' => 1,

            ],
            [
                'nombre' => 'Tinte completo',
                'precio' => 450.00,
                'descripcion' => 'Aplicación de tinte en todo el cabello',
                'activo' => true,
                'imagen' => null,
                'tiempo_estimado' => 90,
                'servicio_id' => 1,

            ],

            [
                'nombre' => 'Manicure',
                'precio' => 120.00,
                'descripcion' => 'Limpieza y arreglo de uñas',
                'activo' => true,
                'imagen' => null,
                'tiempo_estimado' => 40,
                'servicio_id' => 2,
            ],
            [
                'nombre' => 'Pedicure',
                'precio' => 180.00,
                'descripcion' => 'Cuidado y arreglo de pies',
                'activo' => true,
                'imagen' => null,
                'tiempo_estimado' => 50,
                'servicio_id' => 2,
            ],

            [
                'nombre' => 'Lifting de pestañas',
                'precio' => 300.00,
                'descripcion' => 'Levantamiento y definición de pestañas',
                'activo' => true,
                'imagen' => null,
                'tiempo_estimado' => 60,
                'servicio_id' => 3,

            ],
            [
                'nombre' => 'Diseño de cejas',
                'precio' => 100.00,
                'descripcion' => 'Perfilado de cejas',
                'activo' => true,
                'imagen' => null,
                'tiempo_estimado' => 25,
                'servicio_id' => 3,

            ],

            [
                'nombre' => 'Maquillaje social',
                'precio' => 400.00,
                'descripcion' => 'Maquillaje para eventos',
                'activo' => true,
                'imagen' => null,
                'tiempo_estimado' => 60,
                'servicio_id' => 4,

            ],
            [
                'nombre' => 'Maquillaje profesional',
                'precio' => 650.00,
                'descripcion' => 'Maquillaje de larga duración',
                'activo' => true,
                'imagen' => null,
                'tiempo_estimado' => 90,
                'servicio_id' => 4,

            ],

        ]);
    }
}