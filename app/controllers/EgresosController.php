<?php
session_start();
require_once(__DIR__ . '/../models/Egresos.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['idusuario'])) {
        header('Location: ../../index.php');
        exit;
    }
    $idusuario = $_SESSION['idusuario'];
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $descripcion = $_POST['descripcion'] ?? '';
    $monto = $_POST['monto'] ?? 0;
    $observaciones = $_POST['observaciones'] ?? '';

    if (Egreso::registrar($idusuario, $fecha, $descripcion, $monto, $observaciones)) {
        header('Location: /hotelluna/views/index.php');
        exit;
    } else {
        header('Location: ../../views/reportes_usuario/gastos.php?error=1');
        exit;
    }
}