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
            <a href="<?php echo $ruta_base; ?>index.php">Inicio</a> | 
            
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span>👤 Rol: <?php echo $_SESSION['rol']; ?></span> | 
                
                <?php if ($_SESSION['rol'] == 'administrador'): ?>
                    <a href="<?php echo $ruta_base; ?>admin/panel_admin.php">Panel Administrador</a> | 
                <?php endif; ?>
                
                <a href="<?php echo $ruta_base; ?>logout.php">Cerrar Sesión</a>
            
            <?php else: ?>
                <a href="<?php echo $ruta_base; ?>login.php">Iniciar Sesión</a> | 
                <a href="<?php echo $ruta_base; ?>registro.php">Registrarse</a>
            <?php endif; ?>
        </nav>
    </header>
    <hr>