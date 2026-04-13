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
              'nombre' => 'Estilista 1',
            'descripcion' => 'se especializa en maquillaje',
            'user_id' => 1  
        ]);
        Personal::create([
              'nombre' => 'Estilista 2',
            'descripcion' => 'se especializa en peinados',
            'user_id' => 2
        ]);
        Personal::create([
              'nombre' => 'Estilista 3',
            'descripcion' => 'se especializa en cortes de cabello',
            'user_id' => 3 
            
        ]);
    }
}
