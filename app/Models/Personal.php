<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personales';

        protected $fillable = [
        'nombre',
        'descripcion',
        'user_id',    
        ];
    public function especialidades()
{
    return $this->belongsToMany(Especialidad::class);
}
    public function citas()
    {
    return $this->hasMany(Cita::class, 'personal_id');
    }
    public function user()
    {
    return $this->belongsTo(User::class);
    }
}
