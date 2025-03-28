<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    // Deshabilitar el manejo de timestamps
    public $timestamps = false;

    // Los campos que se pueden asignar masivamente
    protected $fillable = [
        'dui', 'id_marcador', 'nombre', 'cargo', 'distrito', 'estado',
    ];

    // Relación con la tabla 'marcaciones'
    public function marcaciones()
    {
        return $this->hasMany(Marcacion::class, 'id_marcador', 'id_marcador');
    }
}
