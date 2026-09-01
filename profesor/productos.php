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

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background: #f5f9fb;
        margin: 0;
    }

    .contenedor {
        max-width: 1100px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .titulo {
        margin: 0 0 8px;
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

    .filtros {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin: 20px 0;
    }

    .mi-boton {
    border-color:white;
    font-size:1em;
    color:white;
    background: linear-gradient(90deg, #2ea3c7, #0d4f78);
    border-radius:15px;
    text-aling:left;
    height: 40px;
    width: 80px;
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    bottom: 20px;
    left: 20px;
}

    #buscador {
        flex: 1;
        min-width: 220px;
        padding: 11px 14px;
        border: 1px solid #cfe3ef;
        border-radius: 12px;
        font-size: 0.95em;
    }

    .filtro {
        border: 1px solid #2ea3c7;
        background: #fff;
        color: #0d4f78;
        border-radius: 999px;
        padding: 9px 16px;
        font-weight: bold;
        cursor: pointer;
    }

    .filtro.activo {
        background: linear-gradient(90deg, #2ea3c7, #0d4f78);
        color: #fff;
        border: none;
    }

    .tarjeta {
        display: flex;
        align-items: center;
        gap: 20px;
        background: #fff;
        border: 1px solid #dfeef5;
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 6px rgba(13, 79, 120, 0.04);
    }

    .tarjeta img {
        width: 90px;
        height: 90px;
        object-fit: contain;
    }

    .info {
        flex: 1;
    }

    .info h3 {
        margin: 0 0 6px;
        color: #123d5b;
    }

    .info p {
        margin: 4px 0;
        color: #234c67;
    }

    .disponible {
        color: #188a45;
        font-weight: bold;
    }

    .sin-stock {
        color: #c43737;
        font-weight: bold;
    }

    .accion {
        text-align: center;
        min-width: 140px;
    }

    .icono {
        display: block;
        font-size: 1.5em;
        margin-bottom: 8px;
    }

    .form-reservar {
        display: flex;
        flex-direction: column;
        gap: 7px;
        align-items: center;
    }

    .form-reservar input {
        width: 70px;
        padding: 5px 7px;
        border: 1px solid #d4e6ef;
        border-radius: 8px;
        text-align: center;
    }

    .btn,
    .btn-disabled {
        border: none;
        border-radius: 18px;
        padding: 8px 16px;
        font-weight: bold;
        font-size: 0.9em;
        cursor: pointer;
    }

    .btn {
        background: linear-gradient(90deg, #2ea3c7, #0d4f78);
        color: #fff;
    }

    .btn-disabled {
        background: #d9d9d9;
        color: #666;
        cursor: not-allowed;
    }

    @media (max-width: 700px) {
        .tarjeta {
            flex-direction: column;
            align-items: flex-start;
        }

        .accion {
            width: 100%;
            text-align: left;
        }
    }
</style>

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
