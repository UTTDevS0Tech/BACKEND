<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    use HasFactory;

    protected $table = 'galeria';

    protected $fillable = [
        'titulo',
        'imagen',
        'categoria_id',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaGaleria::class, 'categoria_id');
    }
}