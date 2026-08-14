<?php
session_start();

// 1. Protección de seguridad: solo administradores
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php"); // Si está dentro de la carpeta admin/
    exit();
}

require '../login/conexion.php';
include '../login/header.php';

// 2. Obtener profesores que están esperando aprobación
$sql = "SELECT id, correo FROM usuarios WHERE rol = 'profesor' AND estado = 'pendiente'";
$stmt = $conexion->query($sql);
$pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <h2>Panel de Control - Administrador</h2>

    <!-- Cartel de confirmación tras realizar una acción -->
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
                            <!-- Botón Aprobar -->
                            <a href="procesar_solicitud.php?id=<?php echo $profesor['id']; ?>&accion=aprobar" 
                               style="color: green; font-weight: bold;">[ Aprobar ]</a>
                            &nbsp; | &nbsp;
                            <!-- Botón Rechazar con alerta de seguridad -->
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
</main>
</body>
</html>