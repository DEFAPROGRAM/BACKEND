<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users'; // Asegúrate de que esto coincida con el nombre de tu tabla en la base de datos

    protected $primaryKey = 'id'; // Asegúrate de que esto coincida con el nombre de la clave primaria en tu tabla

    protected $fillable = [
        'nombres',
        'apellidos',
        'cargo',
        'id_sede',
        'id_juzgado',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}