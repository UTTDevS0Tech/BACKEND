<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{

     protected $fillable = [

        'hora_inicio',
        'hora_fin',
        'personal_id',
        'dia_semana',
        'activo'

     ];



    public function personal() {

        return $this->belongsTo(Personal::class);

    }
}
