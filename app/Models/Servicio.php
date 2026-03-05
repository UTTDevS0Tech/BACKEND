<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
        protected $fillable = [
        'activo',
        'nombre'
    
    ];
public function tiposServicio()
{
    return $this->hasMany(TipoServicio::class, 'servicio_id');
}
}
