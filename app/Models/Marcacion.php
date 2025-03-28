<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Marcacion extends Model
{
    use HasFactory;

    protected $table = 'marcaciones';
    public $timestamps = false;

    protected $fillable = [
        'id_marcador',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'entrada_tardia',
        'salida_temprana',
        'excepciones',
    ];

    // Relación con el modelo Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_marcador', 'id_marcador');
    }

    // Formatear la fecha automáticamente al obtenerla
    public function getFechaAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
}
