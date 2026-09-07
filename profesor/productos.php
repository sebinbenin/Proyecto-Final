<?php
session_start();


if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] != 'profesor') {
    header("Location: ../login/login.php");
    exit();
}

require '../login/conexion.php';


$sql = "SELECT id, nombre, imagen, stock_disponible
        FROM productos
        ORDER BY nombre ASC";
$stmt = $conexion->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="productos.css">

<main class="contenedor">
    <h2 class="titulo">Préstamo de productos</h2>
    <a class="link" href="mis_reservas.php">📋 Ver reservas activas</a>

    <?php if (isset($_GET['res'])): ?>
        <?php if ($_GET['res'] === 'reservado'): ?>
            <p class="alerta alerta-ok">✅ Tu solicitud fue enviada. Espera la aprobación del administrador.</p>
        <?php elseif ($_GET['res'] === 'sin_stock'): ?>
            <p class="alerta alerta-error">❌ No hay stock suficiente para esa reserva.</p>
        <?php elseif ($_GET['res'] === 'error'): ?>
            <p class="alerta alerta-error">❌ Ocurrió un error al procesar la reserva.</p>
        <?php endif; ?>
    <?php endif; ?>

    <div class="filtros">
        <input type="text" id="buscador" placeholder="🔍 Buscar producto...">
        <button class="filtro activo" data-filtro="todos">Todos</button>
        <button class="filtro" data-filtro="disponible">Disponibles</button>
        <button class="filtro" data-filtro="sin_stock">Sin stock</button>
    </div>

    <?php if (count($productos) > 0): ?>
        <?php foreach ($productos as $p):
            $disponible = (int) $p['stock_disponible'] > 0;
            $estado = $disponible ? 'disponible' : 'sin_stock';
        ?>
            <div class="tarjeta" data-estado="<?php echo $estado; ?>" data-nombre="<?php echo strtolower(htmlspecialchars($p['nombre'])); ?>">
                <img src="../Assets/imagenes/productos/<?php echo htmlspecialchars($p['imagen']); ?>"
                     alt="<?php echo htmlspecialchars($p['nombre']); ?>"
                     onerror="this.onerror=null;this.src='../Assets/imagenes/productos/sin_imagen.png';">

                <div class="info">
                    <h3><?php echo htmlspecialchars($p['nombre']); ?></h3>
                    <p>📦 Stock disponible: <strong><?php echo (int) $p['stock_disponible']; ?> unidades</strong></p>
                    <p>🏷️ Estado: <?php echo $disponible ? '<span class="disponible">Disponible</span>' : '<span class="sin-stock">Sin stock</span>'; ?></p>
                </div>

                <div class="accion">
                    <?php if ($disponible): ?>
                        <span class="icono">✅</span>
                        <form method="POST" action="reservar.php" class="form-reservar">
                            <input type="hidden" name="producto_id" value="<?php echo (int) $p['id']; ?>">
                            <input type="number" name="cantidad" value="1" min="1" max="<?php echo (int) $p['stock_disponible']; ?>">
                            <button type="submit" class="btn">Reservar</button>
                        </form>
                    <?php else: ?>
                        <span class="icono">❌</span>
                        <button class="btn-disabled" disabled>No disponible</button>
                    <?php endif; ?>
                </div>
            </div>
            <button onclick="window.location.href='../profesor/panel_profesor.php'" class="mi-boton">Volver</button>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay productos cargados todavía.</p>
    <?php endif; ?>
</main>

<script>
    const buscador = document.getElementById('buscador');
    const filtros = document.querySelectorAll('.filtro');
    const tarjetas = document.querySelectorAll('.tarjeta');
    let filtroActivo = 'todos';

    function aplicarFiltros() {
        const texto = buscador.value.trim().toLowerCase();

        tarjetas.forEach(tarjeta => {
            const nombre = tarjeta.dataset.nombre;
            const estado = tarjeta.dataset.estado;
            const coincideTexto = nombre.includes(texto);
            const coincideFiltro = filtroActivo === 'todos' || estado === filtroActivo;
            tarjeta.style.display = (coincideTexto && coincideFiltro) ? 'flex' : 'none';
        });
    }

    buscador.addEventListener('input', aplicarFiltros);

    filtros.forEach(boton => {
        boton.addEventListener('click', () => {
            filtros.forEach(b => b.classList.remove('activo'));
            boton.classList.add('activo');
            filtroActivo = boton.dataset.filtro;
            aplicarFiltros();
        });
    });
</script>

</body>
</html>
