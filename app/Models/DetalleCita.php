<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCita extends Model
{
        protected $fillable = [
        'cita_id',
        'tipo_servicio_id',
        'precio_capturado'
    ];
    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class);
    }
}
