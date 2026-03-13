<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    protected $fillable = [
    'nombre',
    'precio',
    'descripcion',
    'activo',
    'imagen',
    'tiempo_estimado',
    'servicio_id',
];


  public function servicio()
{
    return $this->belongsTo(Servicio::class, 'servicio_id');
}

public function detallesCita()
{
    return $this->hasMany(DetalleCita::class, 'tipo_servicio_id');
}
}
