<?php
require_once '../../app/config/Conexion.php';

$idhabitacion = isset($_GET['idhabitacion']) ? intval($_GET['idhabitacion']) : 0;
$data = [
  'numero' => '',
  'cliente' => '',
  'inicio' => '',
  'fin' => ''
];

if ($idhabitacion > 0) {
  $pdo = Conexion::getInstancia()->getConexion();
  $sql = "SELECT h.numero, CONCAT(p.nombres, ' ', p.apellidos) AS cliente, 
                 a.fechahorainicio AS inicio, a.fechahorafin AS fin
          FROM habitaciones h
          JOIN alquileres a ON a.idhabitacion = h.idhabitacion
          JOIN clientes c ON a.idcliente = c.idcliente
          JOIN personas p ON c.idpersona = p.idpersona
          WHERE h.idhabitacion = :idhabitacion
            AND a.fechahorafin IS NULL
          ORDER BY a.fechahorainicio DESC
          LIMIT 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['idhabitacion' => $idhabitacion]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    $data['numero'] = $row['numero'];
    $data['cliente'] = $row['cliente'];
    $data['inicio'] = $row['inicio'];
    $data['fin'] = $row['fin'] ?? '';
  }
}

header('Content-Type: application/json');
echo json_encode($data);