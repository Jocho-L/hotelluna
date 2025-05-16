<?php
require_once(__DIR__ . '/../config/Conexion.php');
require_once(__DIR__ . '/../models/Clientes.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchTerm'])) {
    $termino = trim($_POST['searchTerm']);
    $resultados = Cliente::buscarPorNombreODNI($termino);

    header('Content-Type: application/json');
    echo json_encode($resultados); // ← devuelves todos los campos directamente
}

?>
