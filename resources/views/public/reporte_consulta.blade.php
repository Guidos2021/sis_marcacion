@extends('layout')

@section('title', 'Consulta de Asistencia')

@push('css')
<style>
    /* Contenedor principal */
    .container-fluid {
        padding-top: 20px;
        padding-bottom: 20px;
    }

    /* Tarjetas con sombras y bordes redondeados */
    .card {
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        font-weight: bold;
        background-color: #007bff;
        color: white;
        border-radius: 12px 12px 0 0;
    }

    /* Tabla estilizada */
    /* Contenedor de tabla responsive */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-top: 10px;
    }

    .table {
        border-radius: 12px;
        overflow: hidden;
    }

    .table thead {
        background-color: #007bff;
        color: white;
        text-align: center;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table td, .table th {
        text-align: center;
        vertical-align: middle;
        padding: 10px;
    }

    /* Valores importantes en rojo */
    .text-danger {
        color: red !important;
        font-weight: bold;
    }

    /* Botón de impresión */
    .btn-imprimir {
        background: #28a745;
        color: white;
        font-size: 16px;
        padding: 10px 15px;
        border-radius: 8px;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .btn-imprimir:hover {
        background: #218838;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table {
            font-size: 12px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Historial de Asistencia</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('consulta') }}">Consulta</a></li>
        <li class="breadcrumb-item active">Resultado</li>
    </ol>

    <!-- Información del Empleado -->
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-user me-1"></i> Información del Empleado</div>
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $empleado->nombre }}</p>
            <p><strong>DUI:</strong> {{ $empleado->dui }}</p>
            <p><strong>Id Marcador:</strong> {{ $empleado->id_marcador }}</p>
            <p><strong>Cargo:</strong> {{ $empleado->cargo ?? 'No especificado' }}</p>
            <p><strong>Distrito:</strong> {{ $empleado->distrito ?? 'No especificado' }}</p>
        </div>
    </div>

    <!-- Resultado del Reporte -->
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Historial de Asistencia</div>
        <div class="card-body">
            @if($marcaciones->isEmpty())
                <div class="alert alert-warning">No se encontraron registros para este empleado en el rango de fechas seleccionado.</div>
            @else
                <button class="btn btn-imprimir mb-3" onclick="imprimirReporte()">
                    <i class="fas fa-eye"></i> Vista Previa
                </button>

                {{-- <button class="btn btn-primary mb-3" onclick="imprimirReporte()">
                    <i class="fas fa-eye"></i> Vista Previa
                </button> --}}

                <div id="reporte">
                    <div id="reporte" class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora Entrada</th>
                                    <th>Hora Salida</th>
                                    <th>Entrada Tardía</th>
                                    <th>Salida Temprana</th>
                                    <th>Excepciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($marcaciones as $marcacion)
                                    <tr>
                                        <td>{{ $marcacion->fecha }}</td>
                                        <td>{{ $marcacion->hora_entrada }}</td>
                                        <td>{{ $marcacion->hora_salida }}</td>
                                        <td class="{{ $marcacion->entrada_tardia > '00:00:00' ? 'text-danger' : '' }}">
                                            {{ $marcacion->entrada_tardia ?? '00:00:00' }}
                                        </td>
                                        <td class="{{ $marcacion->salida_temprana > '00:00:00' ? 'text-danger' : '' }}">
                                            {{ $marcacion->salida_temprana ?? '00:00:00' }}
                                        </td>
                                        <td>{{ $marcacion->excepciones ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>                    
                    <BR></BR>
                    <!-- Totales -->
                    <div class="mt-3">
                        <strong>Tiempo de Entrada Tardía en el mes: </strong> {{ $totalEntradaTardia }}
                    </div>
                    <div class="mt-3">
                        <strong>Tiempo de Salida Temprana en el mes: </strong> {{ $totalSalidaTemprana }}
                    </div>
                    <div class="mt-3">
                        <strong>Tiempo total de Descuento en el Mes: </strong> {{ $totalDescuentoMes }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    function imprimirReporte() {
        let contenido = document.getElementById("reporte").innerHTML;
        let ventana = window.open('', '', 'height=800, width=1000');

        ventana.document.write('<html><head><title>Reporte de Marcaciones</title>');
        ventana.document.write('<style>');
        ventana.document.write('body { font-family: Arial, sans-serif; margin: 0; padding: 15px; }');
        ventana.document.write('h2 { margin-bottom: 10px; text-align: center; color: #007bff; font-size: 16px; }');
        ventana.document.write('table { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 5px; page-break-inside: auto; }');
        ventana.document.write('th, td { border: 1px solid #007bff; padding: 1px; text-align: center; }');
        ventana.document.write('th { background-color: #007bff; color: white; font-size: 11px; }');
        ventana.document.write('.text-danger { color: red; font-weight: bold; }');
        ventana.document.write('@media print { body { margin: 0; } #btnImprimir { display: none; } }');
        ventana.document.write('.container { padding: 10px; border: 1px solid #007bff; border-radius: 10px; background-color: #f8f9fa; margin-bottom: 15px; margin-top: 0; }');
        ventana.document.write('.container1 { padding: 10px; border: 1px solid #007bff; border-radius: 10px; background-color: #f8f9fa; margin-bottom: 15px; margin-top: 0; }');
        ventana.document.write('.container h3 { color: #007bff; font-size: 16px; margin-bottom: 5px; margin-top: 0; }');  // Reducción del margen superior
        ventana.document.write('.container p { font-size: 15px; margin-bottom: 3px; }');
        ventana.document.write('img { display: block; margin: 0 auto 10px; }');
        ventana.document.write('</style>');
        ventana.document.write('</head><body>');

        // Botón de imprimir
        ventana.document.write('<button id="btnImprimir" onclick="window.print()" style="position: fixed; top: 15px; right: 15px; background: #28a745; color: white; border: none; padding: 20px 25px; font-size: 16px; border-radius: 20px; cursor: pointer;">🖨️ IMPRIMIR</button>');

        // Encabezado de "Información del Empleado"
        ventana.document.write('<img src="{{ asset("img/imgLPE.webp") }}" width="195">');  <!-- Imagen centrada -->
        ventana.document.write('<p style="text-align: center; font-size: 16px; font-weight: bold; margin-top: 10px;">DIRECCION DE GESTION DE TALENTO HUMANO</p>');

        ventana.document.write('<div>');
        // Información del empleado dentro de un cuadro con encabezado                
        ventana.document.write('<div class="container">');  <!-- Cuadro de información del empleado -->
        ventana.document.write('<h3 style="text-align: center; background-color: #007bff; color: white; padding: 5px 0; margin-bottom: 10px; font-size: 16px; border-radius: 10px;">Información del Empleado</h3>');

        // Mostrar Nombre y Dui en una línea
        ventana.document.write('<div style="display: flex; justify-content: space-between; margin-bottom: 10px;">');
        ventana.document.write('<p style="margin: 0; font-size: 15px; margin-bottom: 0px;"><strong>Nombre:</strong> {{ $empleado->nombre }}</p>');
        ventana.document.write('<p style="margin: 0; font-size: 15px; width: 48%;"><strong>DUI:</strong> {{ $empleado->dui }}</p>');
        ventana.document.write('</div>');

        // Mostrar Cargo en la misma línea
        ventana.document.write('<div style="display: flex; justify-content: space-between; margin-bottom: 10px;">');
        ventana.document.write('<p style="margin: 0; font-size: 15px; margin-bottom: 0px;""><strong>Cargo:</strong> {{ $empleado->cargo ?? "No especificado" }}</p>');
        ventana.document.write('<p style="margin: 0; font-size: 15px; width: 48%;"><strong>Distrito:</strong> {{ $empleado->distrito ?? "No especificado" }}</p>');
        ventana.document.write('</div>');

        // Mostrar Id Marcador y Distrito en la misma línea
        ventana.document.write('<div style="display: flex; justify-content: space-between; margin-bottom: 10px;">');
        ventana.document.write('<p style="margin: 0; font-size: 15px; margin-bottom: 0px;"><strong>Código Marcador:</strong> {{ $empleado->id_marcador }}</p>');
        ventana.document.write('</div>');

        ventana.document.write('</div>');
        
        // Agregar contenido del reporte
        ventana.document.write('<div class="container">');
        /*ventana.document.write('<H1 style="text-align: center; font-size: 14px; margin-top: 10px;">REPORTE DE MARCACIONES</H1>');*/
        ventana.document.write('<H1 style="text-align: center; font-size: 14px; margin-top: 10px;">REPORTE DE MARCACIONES - MES DE {{ strtoupper($nombreMes) }}</H1>');

        ventana.document.write(contenido);
        ventana.document.write('</div>');

        ventana.document.write('</body></html>');
        ventana.document.close();
    }
</script>
@endpush