<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require 'conexion.php';

$id = $_SESSION['usuario_id'];

if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    header("Location: perfil.php?foto=error");
    exit();
}

$archivo = $_FILES['foto'];
$extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
$extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

if (!in_array($extension, $extensionesPermitidas) || !getimagesize($archivo['tmp_name']) || $archivo['size'] > 2 * 1024 * 1024) {
    header("Location: perfil.php?foto=error");
    exit();
}

$carpeta = '../Assets/imagenes/perfiles/';

if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

$nombreFoto = 'usuario_' . $id . '_' . time() . '.' . $extension;

if (!move_uploaded_file($archivo['tmp_name'], $carpeta . $nombreFoto)) {
    header("Location: perfil.php?foto=error");
    exit();
}

// Borrar la foto anterior
$stmt = $conexion->prepare("SELECT foto FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$anterior = $stmt->fetchColumn();

if ($anterior && file_exists($carpeta . $anterior)) {
    unlink($carpeta . $anterior);
}

$stmt = $conexion->prepare("UPDATE usuarios SET foto = ? WHERE id = ?");
$stmt->execute([$nombreFoto, $id]);

header("Location: perfil.php");
exit();
?>
