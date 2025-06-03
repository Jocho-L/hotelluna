<?php
require_once '../../app/models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idusuario'])) {
    $idusuario = intval($_POST['idusuario']);
    $exito = Usuario::eliminarUsuario($idusuario);
    if ($exito) {
        header('Location: listar.php?msg=Usuario eliminado');
    } else {
        header('Location: listar.php?error=No se pudo eliminar');
    }
    exit;
} else {
    header('Location: listar.php');
    exit;
}