<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        Rol::create([
            'nombre' => 'Estilista',
            'descripcion' => 'Personal operativo de la estética'
        ]);
        
        Rol::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Dueño del sistema'
        ]);

        Rol::create([
            'nombre' => 'Cliente',
            'descripcion' => 'chilo'
        ]);
    }
}