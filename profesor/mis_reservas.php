<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'profesor') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';
include '../login/header.php';

$sql = "SELECT r.id, r.cantidad, r.fecha_reserva, p.nombre AS nombre_producto
        FROM reservas r
        INNER JOIN productos p ON p.id = r.producto_id
        WHERE r.estado = 'aprobada'
        ORDER BY r.fecha_reserva DESC";
$stmt = $conexion->query($sql);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background: #f5f9fb;
    }

    .contenedor {
        max-width: 900px;
        margin: 30px auto;
        padding: 0 20px;
    }

    h2 {
        margin-top: 0;
        color: #123d5b;
    }

    .link {
        display: inline-block;
        margin-bottom: 18px;
        color: #0d4f78;
        text-decoration: none;
        font-weight: bold;
    }

    .alerta {
        padding: 10px 14px;
        border-radius: 10px;
        margin: 12px 0 18px;
        font-weight: bold;
    }

    .alerta-ok {
        background: #eafaf0;
        color: #1d7a3f;
        border: 1px solid #9ad6ae;
    }

    .alerta-error {
        background: #fff1f1;
        color: #b32525;
        border: 1px solid #f0b7b7;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #dfeef5;
        border-radius: 12px;
        overflow: hidden;
    }

    th, td {
        padding: 12px 14px;
        border-bottom: 1px solid #edf5f9;
        text-align: left;
    }

    th {
        background: #edf8fb;
        color: #123d5b;
    }

    .btn {
        background: linear-gradient(90deg, #2ea3c7, #0d4f78);
        color: #fff;
        border: none;
        border-radius: 18px;
        padding: 8px 16px;
        font-weight: bold;
        cursor: pointer;
    }
</style>

<main class="contenedor">
    <h2>Reservas activas</h2>
    <a class="link" href="productos.php">← Volver</a>

    <?php if (isset($_GET['res'])): ?>
        <?php if ($_GET['res'] === 'devuelto'): ?>
            <p class="alerta alerta-ok">✅ Producto marcado como devuelto. El stock fue actualizado.</p>
        <?php elseif ($_GET['res'] === 'error'): ?>
            <p class="alerta alerta-error">❌ Ocurrió un error al procesar la devolución.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (count($reservas) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
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
                            <form method="POST" action="devolver.php" onsubmit="return confirm('¿Confirmás que el producto fue devuelto?');">
                                <input type="hidden" name="reserva_id" value="<?php echo (int) $r['id']; ?>">
                                <button type="submit" class="btn">Devolver</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>🎉 No tenés productos reservados en este momento.</p>
    <?php endif; ?>
</main>

</body>
</html>
