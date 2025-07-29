<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Autor extends Model
{
    use SoftDeletes;
    protected $table = 'autores'; 
    protected $fillable = ['nombre', 'apellido', 'pais', 'fecha_registro'];

   public function libros()
{
    return $this->belongsToMany(Libro::class);
}
        public function autores()
{
    return $this->belongsToMany(Autor::class);
}
}