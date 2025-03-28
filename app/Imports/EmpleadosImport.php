<?php

namespace App\Imports;

use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmpleadosImport implements ToModel, WithHeadingRow
{
    private $errores = []; // Guardamos los errores para mostrarlos después

    public function model(array $row)
    {
        // Lista de distritos válidos
        $distritosValidos = ['Zacatecoluca', 'San Juan Nonualco', 'San Rafael Obrajuelo'];
        $distrito = in_array($row['distrito'] ?? '', $distritosValidos) ? $row['distrito'] : null;

        // Verificar si el empleado ya existe
        $empleadoExistente = Empleado::where('dui', $row['dui'])->orWhere('id_marcador', $row['id_marcador'])->first();

        if ($empleadoExistente) {
            // Guardamos el error pero seguimos con la importación
            $this->errores[] = "Fila con DUI: {$row['dui']} o ID Marcador: {$row['id_marcador']} ya existe.";
            return null; // No creamos el modelo, solo lo omitimos
        }

        // Si no existe, se crea el nuevo empleado
        return new Empleado([
            'dui' => $row['dui'] ?? null,  
            'id_marcador' => $row['id_marcador'] ?? null,  
            'nombre' => $row['nombre'] ?? null,  
            'cargo' => $row['cargo'] ?? null,  
            'distrito' => $distrito,  
        ]);
    }

    public function getErrores()
    {
        return $this->errores;
    }
}
