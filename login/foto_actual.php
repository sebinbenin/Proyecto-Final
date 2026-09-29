<?php
session_start();

$foto = 'foto-usuario.png';

if (isset($_SESSION['usuario_id'])) {
    require 'conexion.php';
    $stmt = $conexion->prepare("SELECT foto FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $resultado = $stmt->fetchColumn();
    if ($resultado) {
        $foto = 'perfiles/' . $resultado;
    }
}

header("Location: ../Assets/imagenes/" . $foto);
exit();
?>
