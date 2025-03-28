<?php

namespace App\Imports;

use App\Models\Marcacion;
use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Carbon\Carbon;

class MarcacionesImport implements ToModel, WithStartRow
{
    private $empleadosNoEncontrados = []; // Lista de IDs no registrados

    public function startRow(): int
    {
        return 2; // Inicia en la fila 2, ignorando los encabezados
    }

    public function model(array $row)
    {
        $id_marcador = $row[0] ?? null; // Columna 1

        // Verificar si el empleado existe
        $empleadoExiste = Empleado::where('id_marcador', $id_marcador)->exists();

        if (!$empleadoExiste) {
            if (!in_array($id_marcador, $this->empleadosNoEncontrados)) {
                $this->empleadosNoEncontrados[] = $id_marcador;
            }
            return null; // Omitimos la fila si el empleado no existe
        }

        try {
            $fecha = Carbon::createFromFormat('d/m/Y', $row[1])->format('Y-m-d'); // Obtener fecha
        } catch (\Exception $e) {
            return null; 
        }

        // Verificar si ya existe una marcación para ese empleado en esa fecha
        $existeMarcacion = Marcacion::where('id_marcador', $id_marcador)
            ->where('fecha', $fecha)
            ->where('hora_entrada', $row[2] ? Carbon::createFromFormat('H:i', $row[2])->format('H:i:s') : '00:00:00')
            ->where('hora_salida', $row[3] ? Carbon::createFromFormat('H:i', $row[3])->format('H:i:s') : '00:00:00')
            ->exists();

        // Si ya existe una marcación para ese empleado en ese día, omitirla
        if ($existeMarcacion) {
            return null; // No insertamos la marcación ya registrada
        }

        // Si no existe, entonces procedemos a agregarla
        $horaEntrada = $row[2] ? Carbon::createFromFormat('H:i', $row[2])->format('H:i:s') : '00:00:00';
        $horaSalida = $row[3] ? Carbon::createFromFormat('H:i', $row[3])->format('H:i:s') : '00:00:00';
        $entradaTardia = $row[4] ? Carbon::createFromFormat('H:i', $row[4])->format('H:i:s') : '00:00:00';
        $salidaTemprana = $row[5] ? Carbon::createFromFormat('H:i', $row[5])->format('H:i:s') : '00:00:00';

        return new Marcacion([
            'id_marcador'    => $id_marcador,
            'fecha'          => $fecha,
            'hora_entrada'   => $horaEntrada,
            'hora_salida'    => $horaSalida,
            'entrada_tardia' => $entradaTardia,
            'salida_temprana'=> $salidaTemprana,
            'excepciones'    => $row[6] ?? null,
        ]);
    }

    public function getEmpleadosNoEncontrados()
    {
        return $this->empleadosNoEncontrados;
    }
}
