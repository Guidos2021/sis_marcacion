<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('panel')}}"></a>
    <!-- Sidebar Toggle -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>
</nav>

<script>
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        var navbarBrand = document.querySelector('.navbar-brand');
        var sidebarToggle = document.getElementById('sidebarToggle');
        
        // Comprobamos si el menú está abierto o cerrado
        if (document.body.classList.contains('sb-sidenav-toggled')) {
            // Si el menú está abierto, movemos el botón a la derecha
            navbarBrand.parentNode.appendChild(sidebarToggle); // Mover a la derecha
        } else {
            // Si el menú está cerrado, movemos el botón a la izquierda
            navbarBrand.parentNode.insertBefore(sidebarToggle, navbarBrand); // Mover a la izquierda
        }
    });

</script>

<style>
    #sidebarToggle {
        background-color: #4e73df; /* Color de fondo */
        border-radius: 8px; /* Bordes redondeados */
        color: white; /* Color de texto */
        padding: 10px 15px; /* Espaciado interno */
        font-size: 18px; /* Tamaño de la fuente */
        border: none; /* Eliminar borde */
        transition: background-color 0.3s, transform 0.3s; /* Transiciones suaves */
    }

    #sidebarToggle:hover {
        background-color: #2e59d9; /* Color de fondo cuando se pasa el ratón */
        transform: scale(1.05); /* Efecto de ampliación */
    }

    #sidebarToggle:focus {
        outline: none; /* Eliminar el contorno cuando el botón está en foco */
    }
</style>