<?php
require_once '../../app/config/Conexion.php';
$conn = Conexion::getConexion();

// Ejemplo: sumar ingresos y contar personas por mes (ajusta según tu modelo real)
$sql = "
    SELECT
        DATE_FORMAT(alquileres.fechahorainicio, '%M') AS mes,
        SUM(alquileres.valoralquiler) AS ingresos,
        COUNT(alquileres.idalquiler) AS personas
    FROM alquileres
    WHERE YEAR(alquileres.fechahorainicio) = YEAR(CURDATE())
    GROUP BY MONTH(alquileres.fechahorainicio)
    ORDER BY MONTH(alquileres.fechahorainicio)
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$meses = [];
$ingresos = [];
$personas = [];
foreach ($data as $row) {
    $meses[] = $row['mes'];
    $ingresos[] = floatval($row['ingresos']);
    $personas[] = intval($row['personas']);
}

echo json_encode([
    'meses' => $meses,
    'ingresos' => $ingresos,
    'personas' => $personas
]);