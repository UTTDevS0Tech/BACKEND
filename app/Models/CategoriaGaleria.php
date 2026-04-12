<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaGaleria extends Model
{
    use HasFactory;

    protected $table = 'categorias_galeria';

    protected $fillable = [
        'nombre',
    ];

    public function imagenes()
    {
        return $this->hasMany(Galeria::class, 'categoria_id');
    }
}