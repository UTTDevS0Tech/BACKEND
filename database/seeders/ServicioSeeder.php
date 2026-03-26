<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('servicios')->insert([
            [
                'nombre' => 'Cabello',
                'activo' => true,

            ],
            [
                'nombre' => 'Uñas',
                'activo' => true,

            ],
            [
                'nombre' => 'Pestañas y Cejas',
                'activo' => true,

            ],
            [
                'nombre' => 'Maquillaje',
                'activo' => true,

            ],
        ]);
    }
}