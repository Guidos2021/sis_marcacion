<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <!-- Contenedor para la imagen centrada -->
            <div class="d-flex justify-content-center mb-3">
                <img src="{{ asset('img/LAPAZESTE.png') }}" class="img-fluid" alt="Sample image" style="max-width: 60%; height: auto;">
            </div>
            
            <div class="sb-sidenav-menu-heading">PANEL</div>
            <a class="nav-link" href="{{ route('panel')}}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            <div class="sb-sidenav-menu-heading">MANTENIMIENTO</div>

            <!-- Empleados -->
            <a class="nav-link collapsed" href="" data-bs-toggle="collapse" data-bs-target="#collapseEmpleados" aria-expanded="false" aria-controls="collapseEmpleados">
                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                Empleados
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseEmpleados" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('empleados.index')}}">Lista de Empleados</a>                                    
                </nav>
            </div>

            <!-- Marcaciones -->
            <a class="nav-link collapsed" href="" data-bs-toggle="collapse" data-bs-target="#collapseMarcaciones" aria-expanded="false" aria-controls="collapseMarcaciones">
                <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                Marcaciones
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseMarcaciones" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('marcaciones.index')}}">Marcaciones de Empleados</a>                                    
                </nav>
            </div>

            <!-- Reportes -->
            <a class="nav-link collapsed" href="" data-bs-toggle="collapse" data-bs-target="#collapseReportes" aria-expanded="false" aria-controls="collapseReportes">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-line"></i></div>
                Reportes
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseReportes" aria-labelledby="headingTree" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="{{ route('reportes')}}">Generar Reporte</a>                                    
                </nav>
            </div>

            <!-- Usuarios -->
            <!-- <a class="nav-link collapsed" href="" data-bs-toggle="collapse" data-bs-target="#collapseUsuarios" aria-expanded="false" aria-controls="collapseUsuarios">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-user-secret"></i></div>
                Usuarios
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseUsuarios" aria-labelledby="headingFoir" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="#">Lista de Usuarios</a>                                    
                </nav>
            </div> -->
        </div>
    </div>
    
    <!-- Información del usuario y botón de cerrar sesión -->
    <div class="sb-sidenav-footer">
        <!-- Estilo para el texto de bienvenida -->
        <div class="small" style="font-size: 1.2rem; font-family: 'Arial', sans-serif; font-weight: bold; color: #4e73df;">
            Bienvenido
        </div>
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <!-- Estilo mejorado para el botón -->
            <button type="submit" class="btn btn-danger w-100" style="font-size: 1.1rem; font-weight: bold; padding: 10px;">
            <i class="fa-solid fa-right-to-bracket"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</nav>