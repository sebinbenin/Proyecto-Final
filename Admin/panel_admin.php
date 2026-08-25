<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login/login.php"); 
    exit();
}

require '../login/conexion.php';

$sql = "SELECT id, correo FROM usuarios WHERE rol = 'profesor' AND estado = 'pendiente'";
$stmt = $conexion->query($sql);
$pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_reservas = "SELECT reservas.id,
reservas.cantidad,
reservas.fecha_reserva,
productos.nombre AS producto,
usuarios.correo AS profesor
FROM reservas
INNER JOIN productos
ON reservas.producto_id = productos.id
INNER JOIN usuarios
ON reservas.usuario_id = usuarios.id
WHERE reservas.estado = 'pendiente'
ORDER BY reservas.fecha_reserva DESC";

$stmt_reservas = $conexion->query($sql_reservas);
$reservas_pendientes = $stmt_reservas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/main.css">
    <link rel="stylesheet" href="../Admin/admin.css">
    <title>Panel del administrador</title>
</head>
<body>
    <main>
        <section id="panel-izquierdo">
            <h2>Panel administrador</h2>
            <section class="opciones">
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 2.5 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
                </svg><a href="../pagina_principal/pagPrincipal.html">Inicio</a></span>
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg><a>Agregar objeto</a></span>
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                <line x1="2" y1="17" x2="22" y2="17"/>
                <line x1="6" y1="17" x2="4" y2="21"/>
                <line x1="18" y1="17" x2="20" y2="21"/>
                </svg><a>Reservar salones</a></span>
                <span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
                </svg><a>Algo random</a></span>
            </section>
                <span id="cerrar"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" x2="9" y1="12" y2="12"/>
                </svg><a href="../login/logout.php" class="cerrar-sesion">Cerrar sesión</a></span>
        </section>
        <section id="panel-derecho">
            <h2>Solicitudes</h2>
            <section class="filtros">
                <button class="filtro" id="btn-iz"onclick="cambiarPestana('todas', this)">Todas</button>
                <button class="filtro" onclick="cambiarPestana('solicitudes', this)">Inscripciones</button>
                <button class="filtro" id="btn-der"onclick="cambiarPestana('reservas', this)">Reservas</button>
            </section>
    <?php if (isset($_GET['res'])): ?>
            <?php if ($_GET['res'] === 'aprobado'): ?>
        <p style="color: green; font-weight: bold; background: #e6ffe6; padding: 10px; border: 1px solid green;">✅ La cuenta del profesor ha sido APROBADA con éxito.</p>
            <?php elseif ($_GET['res'] === 'rechazado'): ?>
                <p style="color: red; font-weight: bold; background: #ffe6e6; padding: 10px; border: 1px solid red;">❌ La solicitud del profesor ha sido RECHAZADA.</p>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (count($pendientes) > 0): ?>
        <section id="solicitudes" class="contenido">
            <?php foreach ($pendientes as $profesor): ?>
                <span class="solicitud">
                    <span class="datos">
                        <span class="correo"><?php echo $profesor['correo']; ?></span>
                    </span>
                    <span class="acciones">
                        <a href="procesar_solicitud.php?id=<?php echo $profesor['id']; ?>&accion=aprobar"  class="btn-aprobar">Aprobar</a>
                        <a href="procesar_solicitud.php?id=<?php echo $profesor['id']; ?>&accion=rechazar" class="btn-rechazar" onclick="return confirm('¿Estás seguro de que deseas rechazar esta cuenta?');">Rechazar</a>
                    </span>
                </span>
            <?php endforeach; ?>
        </section>
            <?php else: ?>
            <p>No hay solicitudes pendientes.</p>
            <?php endif; ?>
            <?php if (count($reservas_pendientes) > 0): ?>
            <div id="reservas" class="contenido oculto">
                <?php foreach ($reservas_pendientes as $reserva): ?>
                    <div class="reserva">
                        <div class="info-reserva">
                            <span class="profesor"><?php echo htmlspecialchars($reserva['profesor']); ?></span>
                            <span class="producto">Producto: <?php echo htmlspecialchars($reserva['producto']); ?>
                            </span>
                            <span class="cantidad">Cantidad:<?php echo htmlspecialchars($reserva['cantidad']); ?></span>
                            <span class="fecha">Fecha:<?php echo htmlspecialchars($reserva['fecha_reserva']); ?></span>
                        </div>
                        <div class="acciones">
                            <a href="procesar_reserva.php?id=<?php echo $reserva['id']; ?>&accion=aprobar" class="btn-aprobar">Aceptar</a>
                            <a href="procesar_reserva.php?id=<?php echo $reserva['id']; ?>&accion=rechazar" class="btn-rechazar" onclick="return confirm('¿Estás seguro de que deseas rechazar esta reserva?');">Rechazar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No hay reservas pendientes.</p>
            <?php endif; ?>
        </section>
    </main>
    <script src="../Admin/admin.js"></script>
</body>
</html>