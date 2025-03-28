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
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('reportes') }}">Consulta</a></li>
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
        ventana.document.write('body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }');
        ventana.document.write('h2 { margin-bottom: 10px; text-align: center; color: #007bff; font-size: 16px; }');
        ventana.document.write('table { width: 100%; border-collapse: collapse; font-size: 10px; margin-top: 10px; page-break-inside: auto; }');
        ventana.document.write('th, td { border: 1px solid #007bff; padding: 1px; text-align: center; }');
        ventana.document.write('th { background-color: #007bff; color: white; font-size: 11px; }');
        ventana.document.write('.text-danger { color: red; font-weight: bold; }');
        ventana.document.write('@media print { body { margin: 0; } #btnImprimir { display: none; } }');
        ventana.document.write('@page { size: A4; margin: 10mm; }');
        ventana.document.write('</style>');
        ventana.document.write('</head><body>');

        // Botón de imprimir
        ventana.document.write('<button id="btnImprimir" onclick="window.print()" style="position: fixed; top: 10px; right: 10px; background: #28a745; color: white; border: none; padding: 20px 30px; font-size: 24px; border-radius: 10px; cursor: pointer;">🖨️ IMPRIMIR</button>');


        // Encabezado
        ventana.document.write('<p style="text-align: center; font-size: 16px; font-weight: bold; margin-top: 40px;">DIRECCION DE GESTION DE TALENTO HUMANO</p>');
        ventana.document.write('<img src="{{ asset("img/imgLPE.webp") }}" width="195" style="display: block; margin: 0 auto 10px;">');
        ventana.document.write('<p style="text-align: center; font-size: 14px; margin-top: 10px;">REPORTE DE MARCACIONES</p>');

        // Agregar contenido del reporte
        ventana.document.write(contenido);

        ventana.document.write('</body></html>');
        ventana.document.close();
    }
</script>
@endpush


