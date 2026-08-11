<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    // 1. Añadimos ../ para mandarlo al login de la carpeta principal
    header("Location: ../login/login.php"); 
    exit();
}

// 2. Añadimos ../ para buscar estos archivos afuera
require '../login/conexion.php';
include '../login/header.php';

// ... (el resto del código HTML y la tabla quedan exactamente igual) ...
// Buscamos profesores pendientes
$sql = "SELECT id, correo FROM usuarios WHERE rol = 'profesor' AND estado = 'pendiente'";
$stmt = $conexion->query($sql);
$pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <h2>Panel de Control - Administrador</h2>
    <h3>Solicitudes de Profesores</h3>
    
    <?php if (count($pendientes) > 0): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Correo</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($pendientes as $profesor): ?>
                <tr>
                    <td><?php echo $profesor['correo']; ?></td>
                    <td>
                        <a href="procesar_solicitud.php?id=<?php echo $profesor['id']; ?>&accion=aprobar">Aprobar</a> | 
                        <a href="procesar_solicitud.php?id=<?php echo $profesor['id']; ?>&accion=rechazar">Rechazar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No hay solicitudes pendientes.</p>
    <?php endif; ?>
</main>
</body>
</html>