<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar de forma masiva.
     */
    protected $fillable = [
        'dui',
        'nombre',
        'password',
        'estado',
    ];

    /**
     * Ocultar atributos al serializar el modelo.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Convertir atributos a otros tipos de datos.
     */
    protected $casts = [
        'estado' => 'string',
    ];

}
