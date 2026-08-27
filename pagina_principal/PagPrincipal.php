<?php
require '../login/conexion.php';
session_start();
if (isset($_SESSION['rol'])) {
    $rol = $_SESSION['rol'];
} else {
    $rol = null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pagPrincipal.css">
    <link rel="stylesheet" href="../Assets/main.css">
    <title>Utec Tacuarembó</title>
</head>
<body>
    <header id="header"></header>
    <main>
        <article id="contenedor1">
            <section id="informacion">
                <h2>Utec Tacuarembó</h2>
                <p>Centro educativo ubicado en la ciudad de Tacuarembó, especializado en carreras tercearias y bachilleratos tecnicos de UTU. Nuestra vision es preparar a los estuidantes profecionalmente para el mercado laboral <br><br>Se requiere tener aprobado ciclo basico para ingresar a los bachilleratos.<br> Y para cursar niveles tercerarios es necesario tener secundaria completa.</p>
                <?php if ($rol == 'administrador'): ?>
                <button onclick="window.location.href='../Admin/panel_admin.php'">Volver al panel</button>
                <?php elseif ($rol == 'profesor'): ?>
                <button onclick="window.location.href='../profesor/panel_profesor.php'">Volver al panel</button>
            <?php endif; ?>
            </section>
            <section class="carrusel-automatico">
                <span class="cambio slide-automatico">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d4465.1001157147275!2d-55.98918168251975!3d-31.708402264782453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2suy!4v1784290303729!5m2!1ses-419!2suy" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" class="ubi-img"></iframe>
                </span>
                <span class="cambio">
                    <img src="../Assets/imagenes/img_utec.png" alt="imagen de la institucion">
                </span>
            </section>
        </article>
        <h2>Instalaciones</h2>
        <section class="carousel">
        <button class="nav-btn nav-prev" aria-label="Anterior">‹</button>
        <span class="carousel-track" id="track">
            <a href="../biblioteca/biblioteca.html" class="slide" target="_self" rel="noopener">
                <img src="../Assets/imagenes/biblioteca-Photoroom.png" alt="Producto 1">
            </a>
            <a href="../SalonDeCiencias/SalonDeCiencias.html" class="slide" target="_self" rel="noopener">
                <img src="../Assets/imagenes/ciencias-Photoroom.png" alt="Producto 2">
            </a>
            <a href="../anfiteatro/anfiteatro.html" class="slide" target="_self" rel="noopener">
                <img src="../Assets/imagenes/conferencias-Photoroom.png" alt="Producto 3">
            </a>
            <a href="../Laboratorioquimica/laboratorio-quimica.html" class="slide" target="_self" rel="noopener">
                <img src="../Assets/imagenes/quimica-Photoroom.png" alt="Producto 4">
            </a>
            <a href="../SalonDeFisica/SalonDeFisica.html" class="slide" target="_self" rel="noopener">
                <img src="../Assets/imagenes/fisica-Photoroom.png" alt="Producto 4">
            </a>
            <a href="../Laboratorioinformatica/Laboratorio-informatica.html" class="slide" target="_self" rel="noopener">
                <img src="../Assets/imagenes/informatica1-Photoroom.png" alt="Producto 4">
            </a>
        </span>
        <button class="nav-btn nav-next" aria-label="Siguiente">›</button>
        </section>
    </main>
    <footer id="footer"></footer>
    <script src="pagPrincipal.js"></script>
    <script src="../Assets/main.js"></script>
</body>
</html>