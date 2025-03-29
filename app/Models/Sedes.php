<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sedes extends Model
{
    use HasFactory;

    // Define los campos que se pueden asignar masivamente en Sedes
    protected $fillable = [
        'nom_sede',
        'direccion',
        'municipio',
    ];
// la llave primaria para la tabla Sedes
    protected $primaryKey = 'id_sede';
   
}
