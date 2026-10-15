<!-- PANEL IZQUIERDO DEL ALUMNO (se incluye en cada página del alumno) -->
<section id="panel-izquierdo">
    <h2>Panel alumno</h2>
    <section class="opciones">
        <!-- INICIO -->
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 2.5 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 0 2 2H5a2 2 0 0 1-2-2z"/> <polyline points="9 22 9 12 15 12 15 22"/>
            </svg> <a href="../pagina_principal/PagPrincipal.php">Inicio</a>
        </span>
        <!-- MIS HORARIOS -->
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg><a href="../alumno/horarios.php">Mis horarios</a>
        </span>
        <!-- MIS RESERVAS -->
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg><a href="../profesor/mis_reservas.php">Mis reservas</a>
        </span>
        <!-- RESERVAR EQUIPOS -->
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
            </svg><a href="../profesor/productos.php">Reservar equipos</a>
        </span>
        <!-- SALONES -->
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>
            </svg><a href="../profesor/calendario.php">Salones</a>
        </span>
    </section>
    <!-- CERRAR SESIÓN -->
    <span id="cerrar">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/>
            <line x1="21" x2="9" y1="12" y2="12"/>
        </svg><a href="../login/logout.php" class="cerrar-sesion">Cerrar sesión</a>
    </span>
</section>
