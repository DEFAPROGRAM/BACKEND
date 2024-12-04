<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juzgados extends Model
{
    use HasFactory;

    protected $table = 'juzgados';
    protected $primaryKey = 'id_juzgado';

    protected $fillable = [
        'nom_juzgado',
        'id_sede'
    ];

    public function sede()
    {
        return $this->belongsTo(Sedes::class, 'id_sede');
    }

    // Añadir relación con Reservas
    public function reservas()
    {
        return $this->hasMany(Reservas::class, 'id_juzgado');
    }
}