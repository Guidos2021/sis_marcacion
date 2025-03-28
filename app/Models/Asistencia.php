<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';
    
    protected $fillable = [
        'dui',
        'id_marcador',
        'nombre',
        'fecha',
        'horario',
        'hora_entrada',
        'hora_salida',
        'entrada_tardia',
        'salida_temprana',
        'excepciones',
    ];
    
}
