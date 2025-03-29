<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salas extends Model
{
    use HasFactory;

    protected $table = 'salas';
    protected $primaryKey = 'id_sala';

    protected $fillable = [
        'nom_sala',
        'capacidad',
        'id_sede'
    ];

    public function sede()
    {
        return $this->belongsTo(Sedes::class, 'id_sede');
    }

    // Añadir relación con Reservas
    public function reservas()
    {
        return $this->hasMany(Reservas::class, 'id_sala');
    }
}