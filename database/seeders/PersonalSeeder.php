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
            'descripcion' => 'Profesionista en Cosmetologia.',
            'user_id' => 1  
        ]);
        Personal::create([
              'nombre' => 'Diego Reyes',
            'descripcion' => 'Profesionista en el área de la salud, con experiencia en atención al cliente y gestión de citas.',
            'user_id' => 4
        ]);
        Personal::create([
              'nombre' => 'sara elizabet',
            'descripcion' => 'maquillachidote',
            'user_id' => 1 
            
        ]);
    }
}
