<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Personal;

class PersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Personal::create([
              'nombre' => 'Sara Elizabeth',
            'descripcion' => 'Profesional en estetica y cosmetologia',
            'user_id' => 1  
        ]);
        Personal::create([
              'nombre' => 'Diego Reyes',
            'descripcion' => 'Gestor de redes sociales y marketing digital',
            'user_id' => 4
        ]);

    }
}
