<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservas extends Model 
{
    use HasFactory;

    protected $table = 'reservas';
    protected $primaryKey = 'id_reserva';

    protected $fillable = [
        'id_sala',
        'id_juzgado',
        'id_usuario',
        'descripcion',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'observaciones',
        'estado',
    ];

    public function sala()
    {
        return $this->belongsTo(Salas::class, 'id_sala');
    }

    public function juzgado()
    {
        return $this->belongsTo(Juzgados::class, 'id_juzgado');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}