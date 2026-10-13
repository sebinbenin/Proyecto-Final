<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';

$sql = "SELECT r.id, r.cantidad, r.fecha_reserva, p.nombre AS nombre_producto
        FROM reservas r
        INNER JOIN productos p ON p.id = r.producto_id
        WHERE r.estado = 'aprobada'
        ORDER BY r.fecha_reserva DESC";
$stmt = $conexion->query($sql);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="mis_reservas.css">

<main class="contenedor">
    <h2>Reservas activas</h2>
    <a class="link" href="productos.php">← Volver</a>

    <?php if (isset($_GET['res'])): ?>
        <?php if ($_GET['res'] === 'devuelto'): ?>
            <p class="alerta alerta-ok">✅ Equipo marcado como devuelto. El stock fue actualizado.</p>
        <?php elseif ($_GET['res'] === 'error'): ?>
            <p class="alerta alerta-error">❌ Ocurrió un error al procesar la devolución.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (count($reservas) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Equipo</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['nombre_producto']); ?></td>
                        <td><?php echo (int) $r['cantidad']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($r['fecha_reserva'])); ?></td>
                        <td>
                            <form method="POST" action="devolver.php" onsubmit="return confirm('¿Confirmás que el equipo fue devuelto?');">
                                <input type="hidden" name="reserva_id" value="<?php echo (int) $r['id']; ?>">
                                <button type="submit" class="btn">Devolver</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tenés equipos reservados en este momento.</p>
    <?php endif; ?>
<?php
// Solo incluye session_start() si no lo has puesto al principio del archivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ajusta la ruta de conexión según la ubicación de tu archivo
require_once '../login/conexion.php'; 
?>

<div class="contenedor-reservas">
    <h2>📅 Reservas Registradas</h2>
    <hr>

    <?php
    try {
        // Consulta para obtener las reservas ordenadas desde la más reciente
        $sql = "SELECT fecha, salon, hora_inicio, hora_fin, profesor, curso 
                FROM reservas_salones 
                ORDER BY fecha DESC, hora_inicio ASC";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($reservas) > 0): 
    ?>
        <table class="tabla-reservas">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Salón</th>
                    <th>Horario</th>
                    <th>Profesor</th>
                    <th>Curso</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $res): ?>
                <tr>
                    <td><?php echo date("d/m/Y", strtotime($res['fecha'])); ?></td>
                    <td><?php echo htmlspecialchars($res['salon']); ?></td>
                    <td>
                        <?php echo htmlspecialchars($res['hora_inicio']) . " - " . htmlspecialchars($res['hora_fin']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($res['profesor']); ?></td>
                    <td><?php echo htmlspecialchars($res['curso']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php 
        else: 
            echo "<p>No hay reservas registradas en el sistema en este momento.</p>";
        endif;

    } catch (PDOException $e) {
        echo "<p>Error al cargar las reservas: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
</div>
</main>

</body>
</html>
