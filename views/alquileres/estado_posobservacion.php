<?php
require_once(dirname(__DIR__, 2) . '/app/controllers/AlquilerController.php');
$conexion = Conexion::getConexion();

$idalquiler = $_POST['idalquiler'] ?? null;
$idhabitacion = $_POST['idhabitacion'] ?? null;
$nuevo_estado = $_POST['nuevo_estado'] ?? null;
$posobservacion = $_POST['posobservaciones'] ?? '';

if ($idalquiler && $idhabitacion && $nuevo_estado) {
    if (!empty($posobservacion)) {
        guardarPosobservacion($conexion, $idalquiler, $posobservacion);
    }
    cambiarEstadoHabitacion($conexion, $idhabitacion, $nuevo_estado);
    header("Location: ../index.php?mensaje=Estado y posobservación guardados correctamente");
    exit;
} else {
    echo "Datos incompletos.";
}