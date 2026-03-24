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

              'nombre' => 'sara elisabet',
            'descripcion' => 'maquillachidote',
            'user_id' => 1 
            
        ]);
       
    }
}
