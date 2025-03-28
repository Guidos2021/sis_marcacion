@extends('dashboard')

@section('title','marcaciones')

@push('css')
<!-- Agrega tus estilos aquí si es necesario -->
<style>
    /* Estilo para el título */
    .page-title {
        font-size: 28px;
        font-weight: bold;
        color: #2c3e50;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-title i {
        color: #007bff;
        font-size: 32px;
    }

    /* Estilo para el breadcrumb */
    .breadcrumb {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 8px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Estilo para los enlaces del breadcrumb */
    .breadcrumb-item a {
        text-decoration: none;
        color: #007bff;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    @media print {
        .text-danger, .text-danger {
            color: red;
            font-weight: bold;
        }
    }
    /* Responsividad para tablas */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table th,
        .table td {
            white-space: nowrap;
        }
    }

    .upload-form {
        background: #ffffff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: auto;
        border: 1px solid #dee2e6;
    }

    .upload-form label {
        font-weight: bold;
        color: #495057;
    }

    .upload-form .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
    }

    .btn-upload {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
    }

    .btn-upload:hover {
        background-color: #0056b3;
        box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-upload i {
        font-size: 18px;
    }

    /* Contenedor del botón */
    .vaciar-form {
        display: flex;
        justify-content: flex-end;
    }

    /* Estilos del botón */
    .btn-vaciar {
        background-color: #dc3545;
        color: white;
        font-weight: bold;
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        border: none;
    }

    .btn-vaciar:hover {
        background-color: #b02a37;
        box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-vaciar i {
        font-size: 18px;
    }

    /* Estilos para la tarjeta */
    .card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .card-header {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        font-size: 18px;
    }

    /* Estilos de la tabla */
    .table-responsive {
        padding: 10px;
    }

    .table {
        border-radius: 10px;
        overflow: hidden;
        width: 100%;
        text-align: center;
    }

    .table thead {
        background-color: #343a40;
        color: white;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Resaltado de tardanzas y salidas tempranas */
    .text-danger {
        font-weight: bold;
    }

    /* Mejorando el ancho de las columnas */
    th, td {
        padding: 12px;
        white-space: nowrap;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table {
            font-size: 14px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

    <h1 class="page-title mt-4">
        <i class="fas fa-clock"></i> Marcaciones
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Panel</a></li>
        <li class="breadcrumb-item active">Marcaciones</li>
    </ol>

    <!-- Formulario para subir CSV -->
    <div class="upload-form mb-4">
        <form action="{{ route('marcaciones.importar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="archivo_csv" class="form-label">📂 Seleccionar archivo CSV</label>
                <input type="file" name="archivo" accept=".csv" id="archivo_csv" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-upload">
                <i class="fa fa-upload"></i> Subir y Guardar
            </button>
        </form>
    </div>

    <!-- Botón para vaciar la tabla -->
    <div class="vaciar-form mb-4">
        <form action="{{ route('marcaciones.vaciar') }}" method="POST" onsubmit="return confirmarVaciado(event, this)">
            @csrf
            @method('DELETE') 
            <button type="submit" class="btn btn-vaciar">
                <i class="fa fa-trash"></i> Vaciar Marcaciones
            </button>
        </form>
    </div>


    <!-- <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Marcaciones - Alcaldía Municipal La Paz Este
        </div>
        <div class="table-responsive">
            <table id="miTabla" class="display table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID Marcador</th>
                        <th>Fecha</th>
                        <th>Hora Entrada</th>
                        <th>Hora Salida</th>
                        <th>Entrada Tardía</th>
                        <th>Salida Temprana</th>
                        <th>Excepciones</th>
                    </tr>
                </thead>
                <tbody>                
                @foreach($empleados as $empleado)
                    @foreach($empleado->marcaciones as $marcacion)
                        <tr>
                            <td>{{ $marcacion->id_marcador }}</td>
                            <td>{{ $marcacion->fecha }}</td>
                            <td>{{ $marcacion->hora_entrada ?? '' }}</td>
                            <td>{{ $marcacion->hora_salida ?? '' }}</td>
                            <td class="{{ $marcacion->entrada_tardia > '00:00:00' ? 'text-danger' : '' }}">
                                {{ $marcacion->entrada_tardia ?? '00:00:00' }}
                            </td>
                            <td class="{{ $marcacion->salida_temprana > '00:00:00' ? 'text-danger' : '' }}">
                                {{ $marcacion->salida_temprana ?? '00:00:00' }}
                            </td>
                            <td>{{ $marcacion->excepciones ?? '' }}</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div> -->
</div>
@endsection

@push('js1')

<script>
    $(document).ready(function () {
        $('#miTabla').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Mostrar mensaje de éxito
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session("success") }}',
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Aceptar'
        });
    @endif

    // Mostrar mensaje de error
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session("error") }}',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        });
    @endif
</script>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush