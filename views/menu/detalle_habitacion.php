<?php
require_once '../../app/config/Conexion.php';

$idhabitacion = isset($_GET['idhabitacion']) ? intval($_GET['idhabitacion']) : 0;
$data = [];

if ($idhabitacion > 0) {
  $pdo = Conexion::getInstancia()->getConexion();

  // Traer el alquiler activo más reciente de la habitación
  $sql = "SELECT
          a.idalquiler,
          h.idhabitacion AS idhabitacion,
          h.numero AS habitacion_numero,
          h.piso AS habitacion_piso,
          h.numcamas AS habitacion_numcamas,
          h.precioregular AS habitacion_precio,
          h.estado AS habitacion_estado,
          th.tipohabitacion AS habitacion_tipo,
          a.fechahorainicio,
          a.fechahorafin,
          a.lugarprocedencia,
          a.modalidadpago,
          a.valoralquiler,
          a.incluyedesayuno,
          a.observaciones,
          c.idcliente,
          p.nombres AS cliente_nombres,
          p.apellidos AS cliente_apellidos,
          p.numerodoc AS cliente_numerodoc,
          p.telefono AS cliente_telefono,
          ue.username AS usuario_entrada,
          pe.nombres AS entrada_nombres,
          pe.apellidos AS entrada_apellidos,
          us.username AS usuario_salida,
          ps.nombres AS salida_nombres,
          ps.apellidos AS salida_apellidos
        FROM alquileres a
        JOIN clientes c ON a.idcliente = c.idcliente
        JOIN personas p ON c.idpersona = p.idpersona
        JOIN habitaciones h ON a.idhabitacion = h.idhabitacion
        JOIN tipohabitaciones th ON h.idtipohabitacion = th.idtipohabitacion
        JOIN usuarios ue ON a.idusuarioentrada = ue.idusuario
        JOIN personas pe ON ue.idpersona = pe.idpersona
        LEFT JOIN usuarios us ON a.idusuariosalida = us.idusuario
        LEFT JOIN personas ps ON us.idpersona = ps.idpersona
        WHERE h.idhabitacion = :idhabitacion
        ORDER BY a.fechahorainicio DESC
        LIMIT 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['idhabitacion' => $idhabitacion]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row && $row['idalquiler']) {
    // Traer acompañantes (huespedes) de este alquiler
    $acompanantes = [];
    $sqlA = "SELECT per.nombres, per.apellidos, per.numerodoc
             FROM huespedes h
             INNER JOIN personas per ON h.idpersona = per.idpersona
             WHERE h.idalquiler = :idalquiler";
    $stmtA = $pdo->prepare($sqlA);
    $stmtA->execute(['idalquiler' => $row['idalquiler']]);
    $acompanantes = $stmtA->fetchAll(PDO::FETCH_ASSOC);

    $data = [
      'idhabitacion'        => $row['idhabitacion'],
      'habitacion_numero'   => $row['habitacion_numero'],
      'habitacion_piso'     => $row['habitacion_piso'],
      'habitacion_numcamas' => $row['habitacion_numcamas'],
      'habitacion_precio'   => $row['habitacion_precio'],
      'habitacion_estado'   => $row['habitacion_estado'],
      'habitacion_tipo'     => $row['habitacion_tipo'],
      'fechahorainicio'     => $row['fechahorainicio'],
      'fechahorafin'        => $row['fechahorafin'],
      'lugarprocedencia'    => $row['lugarprocedencia'],
      'modalidadpago'       => $row['modalidadpago'],
      'valoralquiler'       => $row['valoralquiler'],
      'incluyedesayuno'     => $row['incluyedesayuno'],
      'observaciones'       => $row['observaciones'],
      'cliente_nombres'     => $row['cliente_nombres'],
      'cliente_apellidos'   => $row['cliente_apellidos'],
      'cliente_numerodoc'   => $row['cliente_numerodoc'],
      'cliente_telefono'    => $row['cliente_telefono'],
      'acompanantes'        => $acompanantes
    ];
  } else {
    $data['error'] = 'No se encontró alquiler activo para esta habitación.';
  }
} else {
  $data['error'] = 'ID de habitación inválido.';
}

header('Content-Type: application/json');
echo json_encode($data);