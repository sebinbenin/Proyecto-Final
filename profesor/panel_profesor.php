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
<title>Panel del Profesor</title>
</head>
<body>
    <div class="panel">
        <!-- MENÚ LATERAL -->
        <aside class="sidebar">
            <nav class="menu">
                <a href="../pagina_principal/PagPrincipal.php">
                    🏠 Inicio
                </a>
                <a href="mis_reservas.php">
                    📅 Mis reservas
                </a>
                <a href="reservar.php">
                    ➕ Reservar
                </a>
                <a href="salones.php">
                    🏫 Salones
                </a>
            </nav>
        </aside>
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
                    <a href="reservar.php" class="boton">Nueva reserva</a>
                </div>
                <div class="tarjeta">
                    <div class="icono">🏫</div>
                    <h2>Salones</h2>
                    <p>Consultá los salones disponibles.</p>
                    <a href="salones.php" class="boton secundario">Ver salones</a>
                </div>
            </section>
                </div>
            </section>
        </main>
    </div>
</body>
</html>