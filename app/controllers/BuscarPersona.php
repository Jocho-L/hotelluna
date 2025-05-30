<?php
require_once(__DIR__ . '/../config/Conexion.php');
$conexion = Conexion::getConexion();

$searchTerm = isset($_POST['searchTerm']) ? trim($_POST['searchTerm']) : '';

$resultados = [];

if ($searchTerm !== '') {
    $stmt = $conexion->prepare(
        "SELECT idpersona, tipodoc, numerodoc, nombres, apellidos 
         FROM personas 
         WHERE numerodoc LIKE ? OR nombres LIKE ? OR apellidos LIKE ? 
         LIMIT 20"
    );
    $like = "%$searchTerm%";
    $stmt->execute([$like, $like, $like]);
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($resultados);