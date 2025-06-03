<?php
require_once '../../app/config/Conexion.php';

$idalquiler = isset($_GET['idalquiler']) ? intval($_GET['idalquiler']) : 0;
$resultado = [];

if ($idalquiler > 0) {
    $conexion = Conexion::getConexion();
    $sql = "SELECT p.nombres, p.apellidos, h.tipohuesped, h.parentesco, h.observaciones
            FROM huespedes h
            JOIN personas p ON h.idpersona = p.idpersona
            WHERE h.idalquiler = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$idalquiler]);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($resultado);