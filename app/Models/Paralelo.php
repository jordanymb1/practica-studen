<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paralelo extends Model
{
     //activar la funcion para poder agregar registro desde este modelo
     protected $fillable=[
        'nombre'
    ];

     //activar la funcion que me permita relacionar con las otras tablas
     public function estudiantes(){
         return $this->HasMany(Estudiante::class);
     }
 
}
