<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
        protected $fillable = [
        'nom',
        'apellido_p',
        'apellido_m',
        'telefono',
        'user_id'

    ];
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
    public function user() {

        return $this->belongsTo(User::class);
    }

}
