<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login/login.php"); 
    exit();
}

require '../login/conexion.php';
include '../login/header.php';

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

<main>
    <h2>Panel de Control - Administrador</h2>

<?php if (isset($_GET['res'])): ?>
        <?php if ($_GET['res'] === 'aprobado'): ?>
    <p style="color: green; font-weight: bold; background: #e6ffe6; padding: 10px; border: 1px solid green;">
                ✅ La cuenta del profesor ha sido APROBADA con éxito.
            </p>
        <?php elseif ($_GET['res'] === 'rechazado'): ?>
            <p style="color: red; font-weight: bold; background: #ffe6e6; padding: 10px; border: 1px solid red;">
                ❌ La solicitud del profesor ha sido RECHAZADA.
            </p>
        <?php endif; ?>
    <?php endif; ?>

    <h3>Solicitudes de Profesores Pendientes</h3>

    <?php if (count($pendientes) > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Correo del Profesor</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendientes as $profesor): ?>
                    <tr>
                        <td><?php echo $profesor['id']; ?></td>
                        <td><?php echo $profesor['correo']; ?></td>
                        <td>
                            
                            <a href="procesar_solicitud.php?id=<?php echo $profesor['id']; ?>&accion=aprobar" 
                               style="color: green; font-weight: bold;">[ Aprobar ]</a>
                            &nbsp; | &nbsp;
                            
                            <a href="procesar_solicitud.php?id=<?php echo $profesor['id']; ?>&accion=rechazar" 
                               style="color: red; font-weight: bold;"
                               onclick="return confirm('¿Estás seguro de que deseas rechazar esta cuenta?');">[ Rechazar ]</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>🎉 No hay solicitudes pendientes por el momento.</p>
    <?php endif; ?>
<hr>
<h3>Solicitudes de Reserva de Productos</h3>
<?php if (count($reservas_pendientes) > 0): ?>
<table border="1" cellpadding="8" cellspacing="0">
<thead>
<tr>
<th>Profesor</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Fecha</th>
<th>Acción</th>
</tr>
</thead>
<tbody>

<?php foreach ($reservas_pendientes as $reserva): ?>
<tr>

                    <td><?php echo $reserva['profesor']; ?></td>

                    <td><?php echo $reserva['producto']; ?></td>

                    <td><?php echo $reserva['cantidad']; ?></td>

                    <td><?php echo $reserva['fecha_reserva']; ?></td>

                    <td>

                        <a href="procesar_reserva.php?id=<?php echo $reserva['id']; ?>&accion=aprobar">
                            Aprobar
                        </a>

                        |

                        <a href="procesar_reserva.php?id=<?php echo $reserva['id']; ?>&accion=rechazar">
                            Rechazar
                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

<?php else: ?>
    <p>No hay reservas pendientes.</p>
<?php endif; ?>
</main>
</body>
</html>