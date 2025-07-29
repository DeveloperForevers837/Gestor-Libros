<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    protected $table = 'libros';

    protected $fillable = [
        'titulo',
        'anio_publicacion',
        'editorial',
        'descripcion'
    ];

    public function autores()
{
    return $this->belongsToMany(Autor::class)
                    ->withTrashed();
}

}
