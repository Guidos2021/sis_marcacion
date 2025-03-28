@extends('dashboard')

@section('title', 'Lista de Empleados')

@push('css')
<!-- Agrega tus estilos aquí si es necesario -->
<style>
.upload-form {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: auto;
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
    }

    .btn-upload:hover {
        background-color: #0056b3;
        box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-upload i {
        font-size: 18px;
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
    .dui-columna {
        white-space: nowrap;
    }

    /* Estilos personalizados */
    .card-header {
        background-color: #007bff;
        color: white;
        font-weight: bold;
    }

    .table thead {
        background-color: #343a40;
        color: white;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-sm {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .btn-warning {
        color: white;
        background-color: #ffc107;
        border: none;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-agregar {
        background-color: #28a745;
        color: white;
        font-weight: bold;
        border-radius: 8px;
        padding: 10px 20px;
        transition: all 0.3s ease;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 8px; /* Espacio entre el icono y el texto */
    }

    .btn-agregar:hover {
        background-color: #218838;
        box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
    }

    .btn-agregar i {
        font-size: 18px;
    }

    /* Estilo para el título */
    .page-title {
        font-size: 28px;
        font-weight: bold;
        color: #343a40;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
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

    /* Estilos para el modal */
    .modal-content {
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #007bff;
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    /* Mejorando los inputs */
    .form-control {
        border-radius: 8px;
        padding: 10px;
        border: 1px solid #ced4da;
    }

    /* Inputs deshabilitados */
    .form-control[readonly] {
        background-color: #e9ecef;
        color: #495057;
        pointer-events: none;
    }

    /* Botón guardar centrado */
    .d-flex.justify-content-center button {
        width: 50%;
        font-size: 16px;
    }

    /* Estilo del select */
    select.form-control {
        cursor: pointer;
    }

    /* Media Query para pantallas pequeñas */
    @media (max-width: 576px) {
        .d-flex.justify-content-center button {
            width: 100%;
        }
    }

    /* Estilo personalizado para el modal */
    .custom-modal .modal-content {
        border-radius: 10px;
        background: #f7f7f7;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .custom-modal .modal-header.custom-header {
        background-color: #007bff;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        color: white;
        padding: 15px;
    }

    .custom-modal .modal-title {
        font-size: 1.3rem;  /* Ajustado para que sea más pequeño */
        font-weight: bold;
    }

    /* Estilo para el botón de guardar */
    .custom-btn {
        font-size: 16px;
        width: 220px;
        height: 45px;
        border-radius: 8px;
        background-color: #28a745;
        border: none;
        color: white;
        font-family: 'Arial', sans-serif;
    }

    .custom-btn:hover {
        background-color: #218838;
    }

    /* Estilo de los campos de formulario */
    .custom-input {
        border-radius: 8px;
    }

    /* Estilo para ajustar el tamaño del modal */
    .modal-dialog.modal-sm {
        max-width: 500px;  /* Tamaño más pequeño */
    }

    /* Responsividad */
    @media (max-width: 576px) {
        .custom-modal .modal-dialog {
            max-width: 100%;
            margin: 15px;
        }

        .custom-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="page-title mt-4">📋 Lista de Empleados</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Panel</a></li>
        <li class="breadcrumb-item active">Empleados</li>
    </ol>

    <!-- Formulario para subir CSV -->
    <div class="upload-form mb-4">
        <form action="{{ route('empleados.importar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="archivo_csv" class="form-label">📂 Seleccionar archivo CSV</label>
                <input type="file" name="archivo" accept=".csv" id="archivo_csv" class="form-control" required>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-upload">
                    <i class="fa fa-upload"></i> Subir y Guardar
                </button>
            </div>
        </form>
    </div>

    <!-- Botón para abrir el modal de registro -->
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-agregar mb-4" data-bs-toggle="modal" data-bs-target="#modalEmpleado">
            <i class="fas fa-plus-square"></i> Agregar Empleado
        </button>
    </div>


    <div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Lista de Empleados - Alcaldía Municipal La Paz Este
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="miTabla" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="dui-columna">DUI</th>
                        <th>ID Marcador</th>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Distrito</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleados as $empleado)
                    <tr>
                        <td class="dui-columna">{{ $empleado->dui }}</td>
                        <td>{{ $empleado->id_marcador }}</td>
                        <td>{{ $empleado->nombre }}</td>
                        <td>{{ $empleado->cargo }}</td>
                        <td>{{ $empleado->distrito }}</td>
                        <td>
                            <div class="d-flex gap-2"> 
                                <!-- Botón Editar -->
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" 
                                    data-bs-target="#editarEmpleado{{ $empleado->id }}">
                                    <i class="fas fa-edit"></i> 
                                </button>

                                <!-- Botón Eliminar -->
                                <form action="{{ route('empleados.eliminar', $empleado->id) }}" method="POST" 
                                    onsubmit="confirmarEliminacion(event, this)">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL DE EDICIÓN DE EMPLEADO -->
                    <div class="modal fade" id="editarEmpleado{{ $empleado->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <!-- ENCABEZADO -->
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="fas fa-edit"></i> Editar Empleado</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- CUERPO DEL MODAL -->
                                <div class="modal-body">
                                    <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- DUI -->
                                        <div class="mb-3">
                                            <label class="form-label">DUI</label>
                                            <input type="text" class="form-control" name="dui" value="{{ $empleado->dui }}" required maxlength="10" readonly>
                                        </div>

                                        <!-- ID MARCADOR -->
                                        <div class="mb-3">
                                            <label class="form-label">ID Marcador</label>
                                            <input type="text" class="form-control" name="id_marcador" value="{{ $empleado->id_marcador }}" maxlength="4" readonly>
                                        </div>

                                        <!-- NOMBRE -->
                                        <div class="mb-3">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" class="form-control" name="nombre" value="{{ $empleado->nombre }}">
                                        </div>

                                        <!-- CARGO -->
                                        <div class="mb-3">
                                            <label class="form-label">Cargo</label>
                                            <input type="text" class="form-control" name="cargo" value="{{ $empleado->cargo }}">
                                        </div>

                                        <!-- DISTRITO -->
                                        <div class="mb-3">
                                            <label class="form-label">Distrito</label>
                                            <select class="form-control" name="distrito">
                                                <option value="Zacatecoluca" {{ $empleado->distrito == 'Zacatecoluca' ? 'selected' : '' }}>Zacatecoluca</option>
                                                <option value="San Juan Nonualco" {{ $empleado->distrito == 'San Juan Nonualco' ? 'selected' : '' }}>San Juan Nonualco</option>
                                                <option value="San Rafael Obrajuelo" {{ $empleado->distrito == 'San Rafael Obrajuelo' ? 'selected' : '' }}>San Rafael Obrajuelo</option>
                                            </select>
                                        </div>

                                        <!-- BOTÓN GUARDAR -->
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa-solid fa-floppy-disk"></i> Guardar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="modalEmpleado" tabindex="-1" aria-labelledby="modalEmpleadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content custom-modal">
            <div class="modal-header custom-header">
                <h5 class="modal-title" id="modalEmpleadoLabel">Registrar Nuevo Empleado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEmpleado" action="{{ route('empleados.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="dui" class="form-label">DUI</label>
                        <input type="text" class="form-control custom-input" id="dui" name="dui" required maxlength="10" 
                            oninput="formatearDUI(this)" onkeypress="return soloNumeros(event)">
                    </div>
                    <div class="mb-3">
                        <label for="id_marcador" class="form-label">ID Marcador</label>
                        <input type="text" class="form-control custom-input" id="id_marcador" name="id_marcador" inputmode="numeric" maxlength="4" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control custom-input" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="cargo" class="form-label">Cargo</label>
                        <input type="text" class="form-control custom-input" id="cargo" name="cargo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Distrito</label>
                        <select class="form-control custom-input" name="distrito" required>
                            <option value="" disabled selected>Selecciona un distrito</option>
                            <option value="Zacatecoluca">Zacatecoluca</option>
                            <option value="San Juan Nonualco">San Juan Nonualco</option>
                            <option value="San Rafael Obrajuelo">San Rafael Obrajuelo</option>
                        </select>
                    </div>  
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-success btn-sm custom-btn">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Guardar
                        </button>
                    </div> 
                </form>                    
            </div>
        </div>
    </div>
</div>


</div>
@endsection

@push('js1')

<script>
    $(document).ready(function () {
    $('#miTabla').DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
        },
        searching: true,  // Asegura que la búsqueda esté activada
        order: [[0, 'asc']],  // Puedes personalizar el orden de las columnas
        lengthMenu: [10, 25, 50, 100],  // Opciones para la cantidad de registros por página
        pageLength: 10,  // Número de filas por página
    });
});

</script>

<script>
    document.getElementById('id_marcador').addEventListener('input', function(e) {
        // Reemplaza cualquier carácter no numérico
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limita la longitud a 4 dígitos
        if (this.value.length > 4) {
            this.value = this.value.substring(0, 4);
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function soloNumeros(event) {
        let charCode = event.which ? event.which : event.keyCode;
        // Permitir solo números (0-9) y la tecla de retroceso (Backspace)
        if (charCode < 48 || charCode > 57) {
            return false;
        }
        return true;
    }

    function formatearDUI(input) {
        let valor = input.value.replace(/\D/g, ''); // Eliminar caracteres no numéricos

        if (valor.length > 8) {
            valor = valor.substring(0, 8) + '-' + valor.substring(8, 9);
        }

        input.value = valor; // Asignar el nuevo valor con formato
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmarEliminacion(event, form) {
        event.preventDefault(); // Evita el envío inmediato del formulario

        Swal.fire({
            title: "¿Eliminar empleado?",
            text: "Deseas eliminar al empleado.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, Eliminar ",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Envía el formulario si se confirma la acción
            }
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    let empleados = @json($empleados); // Convertimos la lista de empleados a un array en JS

    // Validación en la creación
    document.getElementById("formEmpleado")?.addEventListener("submit", function (event) {
        event.preventDefault(); // Evita el envío automático del formulario

        let dui = document.getElementById("dui").value.trim();
        let id_marcador = document.getElementById("id_marcador").value.trim();

        let duiExistente = empleados.some(emp => emp.dui === dui);
        let idMarcadorExistente = empleados.some(emp => emp.id_marcador == id_marcador);

        if (duiExistente) {
            Swal.fire({
                title: "Error",
                text: "El número de DUI ya está registrado.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            return;
        }

        if (idMarcadorExistente) {
            Swal.fire({
                title: "Error",
                text: "El número de ID Marcador ya está registrado.",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
            return;
        }

        this.submit(); // Si todo está bien, enviar el formulario
    });
});
</script>

@if(session('success'))
    <script>
        Swal.fire({
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            title: '¡Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
    </script>
@endif
@endpush