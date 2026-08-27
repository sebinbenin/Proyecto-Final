<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Define la URL base de tu proyecto
$ruta_base = "http://localhost/Proyecto-Final/";
?>
<!DOCTYPE html>
<html lang="es">
<!-- ... (head y estilos) ... -->
<body>
    <header>
        <nav>
            <!-- Ahora le pegamos $ruta_base a TODOS los enlaces -->
            <a href="<?php echo $ruta_base; ?>pagina_principal/PagPrincipal.php">Inicio</a>
            
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span> Rol: <?php echo $_SESSION['rol']; ?></span> 
                <a href="<?php echo $ruta_base; ?>login/logout.php">Cerrar Sesión</a>
            <?php endif; ?>
        </nav>
    </header>
<hr>