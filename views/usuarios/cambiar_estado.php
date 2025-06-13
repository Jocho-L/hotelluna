<?php
require_once '../../app/models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idusuario'], $_POST['estado'])) {
    $idusuario = intval($_POST['idusuario']);
    $estado = ($_POST['estado'] === 'activo') ? 'activo' : 'inactivo';
    $exito = Usuario::cambiarEstadoUsuario($idusuario, $estado);
    if ($exito) {
        header('Location: /hotelluna/views/index.php');
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo cambiar el estado']);
    }
    exit;
} else {
    echo json_encode(['success' => false, 'error' => 'Petición inválida']);
    exit;
}