<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login/login.php");
    exit();
}
require '../login/conexion.php';

// ================================
// SOLICITUDES DE PROFESORES
// ================================

$sql = "SELECT id, correo, nombre
        FROM usuarios
        WHERE rol = 'profesor'
        AND estado = 'pendiente'";

$stmt = $conexion->query($sql);
$pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ================================
// RESERVAS PENDIENTES
// ================================

$sql_reservas = "SELECT
                    reservas.id,
                    reservas.cantidad,
                    reservas.fecha_reserva,
                    productos.nombre AS producto,
                    usuarios.correo AS profesor
                    FROM reservas
                    INNER JOIN productos
                    ON reservas.producto_id = productos.id
                    INNER JOIN usuarios
                    ON reservas.usuario_id = usuarios.id
                    WHERE reservas.estado = 'pendiente'";

$stmt_reservas = $conexion->query($sql_reservas);
$reservas_pendientes = $stmt_reservas->fetchAll(PDO::FETCH_ASSOC);


// ================================
// PRODUCTOS
// ================================

$sql_productos = "SELECT
                    id,
                    nombre,
                    descripcion,
                    imagen,
                    categoria,
                    stock_total,
                    stock_disponible
                    FROM productos
                    ORDER BY nombre ASC";

$stmt_productos = $conexion->query($sql_productos);
$productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

// ================================
// PROFESORES Y CURSOS
// ================================

$sql_profesores = "SELECT id, nombre, correo, foto
                    FROM usuarios
                    WHERE rol = 'profesor'
                    AND estado = 'aprobado'
                    ORDER BY nombre ASC";

$stmt_profesores = $conexion->query($sql_profesores);
$profesores = $stmt_profesores->fetchAll(PDO::FETCH_ASSOC);

$sql_asignaciones = "SELECT
                        profesor_curso.profesor_id,
                        profesor_curso.curso_id,
                        profesor_curso.materia,
                        cursos.nombre AS curso
                        FROM profesor_curso
                        INNER JOIN cursos
                        ON profesor_curso.curso_id = cursos.id
                        ORDER BY cursos.nombre ASC";

$stmt_asignaciones = $conexion->query($sql_asignaciones);
$asignaciones = $stmt_asignaciones->fetchAll(PDO::FETCH_ASSOC);

$stmt_cursos = $conexion->query("SELECT id, nombre FROM cursos ORDER BY nombre ASC");
$cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);

$vista = $_GET['vista'] ?? 'solicitudes';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Assets/main.css">
    <link rel="stylesheet" href="../Admin/admin.css">
    <script src="../Assets/tema.js"></script>
    <title>Panel del administrador</title>
</head>
<body>
    <main>
    <!-- =========================================
        PANEL IZQUIERDO
    ========================================= -->
        <section id="panel-izquierdo">
            <h2>Panel administrador</h2>
            <section class="opciones">
                <!-- INICIO -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 2.5 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 0 2 2H5a2 2 0 0 1-2-2z"/> <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg> <a href="../pagina_principal/pagPrincipal.php">Inicio</a>
                </span>
                <!-- AGREGAR PRODUCTO -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg><a onclick="mostrarProductos(event)">Agregar producto</a></span>
                <!-- RESERVAR SALONES -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                        <line x1="2" y1="17" x2="22" y2="17"/><line x1="6"y1="17"x2="4"y2="21"/>
                        <line x1="18" y1="17" x2="20" y2="21"/>
                    </svg><a href="../profesor/calendario.php">Reservar salones</a>
                </span>
                <!-- usaurio -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg><a onclick="mostrarSolicitudes(event)">Solicitudes</a>
                </span>
                <!-- PROFESORES -->
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg><a onclick="mostrarProfesores(event)">Profesores</a>
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
<!-- =========================================
    PANEL DERECHO
========================================= -->
        <section id="panel-derecho">
            <!-- =====================================
                VISTA SOLICITUDES / RESERVAS
            ====================================== -->
            <div id="vista-solicitudes" <?php if ($vista === 'profesores') echo 'class="oculto"'; ?>>
                <h2 id="titulo-panel">Solicitudes</h2>
                <!-- FILTROS -->
                <section class="filtros">
                    <button class="filtro active" id="btn-iz" onclick="cambiarPestana('todas', this)">Todas</button>
                    <button class="filtro" onclick="cambiarPestana('solicitudes', this)">Inscripciones</button>
                    <button class="filtro" id="btn-der" onclick="cambiarPestana('reservas', this)">Reservas</button>
                </section>
                <!-- =================================
                    SOLICITUDES
                ================================== -->
                <section id="solicitudes" class="contenido oculto">
                    <?php if (count($pendientes) > 0): ?>
                        <?php foreach ($pendientes as $profesor): ?>
                            <span class="solicitud">
                                <span class="info-reserva">
                                    <span class="nombre"><?php echo htmlspecialchars($profesor['nombre']);?></span>
                                    <span class="correo"><?php echo htmlspecialchars($profesor['correo']);?></span>
                                </span>
                                <span class="acciones">
                                    <a href="procesar_solicitud.php?id=<?php echo $profesor['id']; ?>&accion=aprobar" class="btn-aprobar">Aprobar</a>
                                    <a href="procesar_solicitud.php?id=<?php echo $profesor['id']; ?>&accion=rechazar" class="btn-rechazar" onclick="return confirm('¿Estás seguro de que deseas rechazar esta cuenta?');">Rechazar</a>
                                </span>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay solicitudes pendientes.</p>
                    <?php endif; ?>
                </section>
                <!-- =================================
                    RESERVAS
                ================================== -->
                <div id="reservas"class="contenido oculto">
                    <?php if (count($reservas_pendientes) > 0): ?>
                        <?php foreach ($reservas_pendientes as $reserva): ?>
                            <div class="reserva">
                                <div class="info-reserva">
                                    <span class="profesor"><?php echo htmlspecialchars($reserva['profesor']);?></span>
                                    <span class="producto">Producto:<?php echo htmlspecialchars($reserva['producto']);?></span>
                                    <span class="cantidad">Cantidad:<?php echo htmlspecialchars($reserva['cantidad']);?></span>
                                    <span class="fecha">Fecha:<?php echo htmlspecialchars($reserva['fecha_reserva']);?></span>
                                </div>
                                <div class="acciones"><a href="procesar_reserva.php?id=<?php echo $reserva['id']; ?>&accion=aprobar"class="btn-aprobar">Aceptar</a>
                                    <a href="procesar_reserva.php?id=<?php echo $reserva['id']; ?>&accion=rechazar" class="btn-rechazar" onclick="return confirm('¿Estás seguro de que deseas rechazar esta reserva?');"> Rechazar </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay reservas pendientes.</p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- =========================================
                VISTA PRODUCTOS
            ========================================== -->
            <div id="vista-productos" class="oculto">
                <h2>Agregar producto</h2>
                <!-- =================================
                    FORMULARIO
                ================================== -->
                <form action="agregar_producto.php" method="POST" enctype="multipart/form-data" class="form-producto">
                    <div class="formulario-contenido">
                        <!-- DATOS -->
                        <div class="datos-producto">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombre" name="nombre" required>
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion"name="descripcion"required></textarea>
                            <label for="categoria">Categoría</label>
                            <select id="categoria" name="categoria" required>
                                <option value="">Seleccionar categoría</option>
                                <option value="Audio">Audio</option>
                                <option value="Audiovisual">Audiovisual</option>
                                <option value="Electricidad">Electricidad</option>
                                <option value="Informática">Informática</option>
                                <option value="Accesorios">Accesorios</option>
                                <option value="Material didáctico">Material didáctico</option>
                            </select>
                            <label for="stock">Stock inicial</label>
                            <input type="number"id="stock"name="stock"min="0"required>
                        </div>
                        <!-- IMAGEN -->
                        <div class="imagen-producto">
                            <label for="imagen">Imagen</label>
                            <input type="file"id="imagen"name="imagen" accept="image/*" required>
                        </div>
                    </div>
                    <button type="submit"class="btn-subir-producto">Subir producto</button>
                </form>
                <!-- =================================
                    PRODUCTOS EXISTENTES
                ================================== -->
                <section class="lista-productos">
                    <h2>Productos existentes</h2>
                    <?php if (count($productos) > 0): ?>
                        <?php foreach ($productos as $producto): ?>
                            <div class="producto-stock">
                                <!-- IMAGEN + NOMBRE -->
                                <div class="producto-info">
                                    <?php if (!empty($producto['imagen'])): ?>
                                        <img src="../Assets/imagenes/productos/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                    <?php endif; ?>
                                    <div>
                                        <h3><?php echo htmlspecialchars($producto['nombre']);?></h3>
                                        <span><?php echo htmlspecialchars($producto['categoria']);?>
                                        </span>
                                    </div>
                                </div>
                                <!-- STOCK -->
                                <div class="stock-info">
                                    <span>Stock:<strong><?php echo $producto['stock_total'];?></strong></span>
                                    <span>Disponible:<strong><?php echo $producto['stock_disponible'];?></strong></span>
                                </div>
                                <!-- CAMBIAR STOCK -->
                                <form action="cambiar_stock.php" method="POST" class="form-stock">
                                    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                                    <input type="number" name="cantidad" min="1" placeholder="Cantidad"required>
                                    <button type="submit" name="accion" value="agregar">+</button>
                                    <button type="submit"name="accion" value="quitar">-</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay productos registrados.</p>
                    <?php endif; ?>
                </section>
            </div>
            <!-- =========================================
                VISTA PROFESORES
            ========================================== -->
            <div id="vista-profesores" <?php if ($vista !== 'profesores') echo 'class="oculto"'; ?>>
                <h2>Profesores</h2>
                <!-- NUEVO CURSO -->
                <form action="asignar_curso.php" method="POST">
                    <input type="hidden" name="accion" value="nuevo_curso">
                    <input type="text" name="nombre_curso" placeholder="Nuevo curso (ej: 3.º MB)" required>
                    <button type="submit">Crear curso</button>
                </form>
                <!-- LISTA DE PROFESORES -->
                <?php if (count($profesores) > 0): ?>
                    <?php foreach ($profesores as $profesor): ?>
                        <div class="reserva">
                            <div class="info-reserva">
                                <img src="../Assets/imagenes/<?php echo $profesor['foto'] ? 'perfiles/' . htmlspecialchars($profesor['foto']) : 'foto-usuario.png'; ?>" alt="Foto" width="60" height="60">
                                <span class="profesor"><?php echo htmlspecialchars($profesor['nombre'] ?? 'Sin nombre'); ?></span>
                                <span class="correo"><?php echo htmlspecialchars($profesor['correo']); ?></span>
                                <?php $tiene_cursos = false; ?>
                                <?php foreach ($asignaciones as $asignacion): ?>
                                    <?php if ($asignacion['profesor_id'] == $profesor['id']): ?>
                                        <?php $tiene_cursos = true; ?>
                                        <span>
                                            <?php echo htmlspecialchars($asignacion['curso']); ?> (<?php echo htmlspecialchars($asignacion['materia']); ?>)
                                            <a href="asignar_curso.php?accion=quitar&profesor_id=<?php echo $profesor['id']; ?>&curso_id=<?php echo $asignacion['curso_id']; ?>&materia=<?php echo urlencode($asignacion['materia']); ?>" onclick="return confirm('¿Quitar este curso?');">Quitar</a>
                                        </span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if (!$tiene_cursos): ?>
                                    <span>Sin cursos asignados</span>
                                <?php endif; ?>
                            </div>
                            <!-- ASIGNAR CURSO -->
                            <form action="asignar_curso.php" method="POST">
                                <input type="hidden" name="accion" value="asignar">
                                <input type="hidden" name="profesor_id" value="<?php echo $profesor['id']; ?>">
                                <select name="curso_id" required>
                                    <option value="">Curso</option>
                                    <?php foreach ($cursos as $curso): ?>
                                        <option value="<?php echo $curso['id']; ?>"><?php echo htmlspecialchars($curso['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="materia" placeholder="Materia" required>
                                <button type="submit">Asignar</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay profesores aprobados.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <script src="../Admin/admin.js"></script>
</body>
</html>