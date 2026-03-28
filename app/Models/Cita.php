<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
        protected $fillable = [
        'total',
        'apartado',
        'personal_id',
        'hora_c',
        'fecha_c',
        'estado',
        'cliente_id'
    ];
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCita::class);
    }
}
