<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'profesor') {
header("Location: ../login/login.php");
exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../profesor/panel_profesor.css">
<link rel="stylesheet" href="../Assets/main.css">
<script src="../Assets/tema.js"></script>
<title>Panel del Profesor</title>
</head>
<body>
    <div class="panel">
        <!-- PANEL IZQUIERDO -->
        <section id="panel-izquierdo">
            <h2>Panel profesor</h2>
            <section class="opciones">
                <!-- INICIO -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 2.5 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 0 2 2H5a2 2 0 0 1-2-2z"/> <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg> <a href="../pagina_principal/PagPrincipal.php">Inicio</a>
                </span>
                <!-- MIS RESERVAS -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg><a href="mis_reservas.php">Mis reservas</a>
                </span>
                <!-- RESERVAR -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                    </svg><a href="productos.php">Reservar</a>
                </span>
                <!-- MIS ALUMNOS -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg><a href="mis_alumnos.php">Mis alumnos</a>
                </span>
                <!-- SALONES -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/>
                    </svg><a href="calendario.php">Salones</a>
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
        <!-- CONTENIDO -->
        <main class="contenido">
            <header class="encabezado">
                <div>
                    <h1>Panel del Profesor</h1>
                    <p>Bienvenido al sistema de reservas.</p>
                </div>
            </header>
            <!-- TARJETAS -->
            <section class="tarjetas">
                <div class="tarjeta">
                    <div class="icono">📅</div>
                    <h2>Mis reservas</h2>
                    <p>Consultá tus reservas realizadas.</p>
                    <a href="mis_reservas.php" class="boton">Ver reservas</a>
                </div>
                <div class="tarjeta">
                    <div class="icono">➕</div>
                    <h2>Reservar</h2>
                    <p>Realizá una nueva reserva.</p>
                    <a href="productos.php" class="boton">Nueva reserva</a>
                </div>
                <div class="tarjeta">
                    <div class="icono">🎓</div>
                    <h2>Mis alumnos</h2>
                    <p>Consultá los alumnos de las clases que dictás.</p>
                    <a href="mis_alumnos.php" class="boton">Ver alumnos</a>
                </div>
                <div class="tarjeta">
                    <div class="icono">🏫</div>
                    <h2>Salones</h2>
                    <p>Consultá los salones disponibles.</p>
                    <a href="../profesor/calendario.php" class="boton">Ver salones</a>
                </div>
            </section>
                </div>
            </section>
        </main>
    </div>
</body>
</html>