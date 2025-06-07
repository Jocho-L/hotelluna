<?php
require_once(__DIR__ . '/../config/Conexion.php');
$conexion = Conexion::getConexion();

require_once(__DIR__ . '/../models/Alquiler.php');

// Obtener el idhabitacion desde GET
$idhabitacion = isset($_GET['idhabitacion']) ? intval($_GET['idhabitacion']) : null;
$alquiler = null;

if ($idhabitacion) {
    $alquiler = obtenerDetalleAlquilerPorHabitacion($conexion, $idhabitacion);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idcliente'])) {
    $idpersona = intval($_POST['idcliente']);

    // Verificar si la persona ya es cliente
    $stmt = $conexion->prepare("SELECT idcliente FROM clientes WHERE idpersona = ?");
    $stmt->execute([$idpersona]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        $idcliente = $cliente['idcliente'];
    } else {
        // Insertar como cliente
        $stmt = $conexion->prepare("INSERT INTO clientes (idpersona) VALUES (?)");
        $stmt->execute([$idpersona]);
        $idcliente = $conexion->lastInsertId();
    }

    // Registrar el alquiler
    $idhabitacion = intval($_GET['idhabitacion']);
    $idusuarioentrada = $_SESSION['idusuario'] ?? 1; // Ajusta según tu sistema de login
    $fechainicio = $_POST['fechainicio'];
    $fechafin = $_POST['fechafin'];
    $modalidadpago = $_POST['modalidadpago'];
    $lugarprocedencia = $_POST['lugarprocedencia'];
    $observaciones = $_POST['observaciones'] ?? null;
    $incluyedesayuno = isset($_POST['incluyedesayuno']) ? 1 : 0;
    $total = $_POST['total'];

    $stmt = $conexion->prepare("INSERT INTO alquileres
        (idcliente, idhabitacion, idusuarioentrada, fechahorainicio, fechahorafin, valoralquiler, modalidadpago, lugarprocedencia, observaciones, incluyedesayuno)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $idcliente,
        $idhabitacion,
        $idusuarioentrada,
        $fechainicio,
        $fechafin,
        $total,
        $modalidadpago,
        $lugarprocedencia,
        $observaciones,
        $incluyedesayuno
    ]);
    $idalquiler = $conexion->lastInsertId();

    // Procesa acompañantes como huéspedes
    if (!empty($_POST['acompanantes_json'])) {
        $acompanantes = json_decode($_POST['acompanantes_json'], true);
        if (is_array($acompanantes)) {
            foreach ($acompanantes as $idpersonaAcompanante) {
                // Evita registrar al cliente principal como acompañante
                if ($idpersonaAcompanante == $idpersona) continue;
                $stmt = $conexion->prepare("INSERT INTO huespedes (idalquiler, idpersona, tipohuesped, observaciones) VALUES (?, ?, 'acompañante', 'acompañante')");
                $stmt->execute([$idalquiler, $idpersonaAcompanante]);
            }
        }
    }

    // Registrar al cliente principal como huésped tipo "cliente"
    $stmt = $conexion->prepare("INSERT INTO huespedes (idalquiler, idpersona, tipohuesped) VALUES (?, ?, 'cliente')");
    $stmt->execute([$idalquiler, $idpersona]);

    // Redirigir o mostrar mensaje de éxito
    header("Location: /hotelluna/views/alquileres/index.php?mensaje=Alquiler registrado correctamente");
    exit;
}

/**
 * Guarda una posobservación para un alquiler.
 */
function guardarPosobservacion($conexion, $idalquiler, $texto) {
    $stmt = $conexion->prepare("UPDATE alquileres SET posobservaciones = ? WHERE idalquiler = ?");
    $stmt->execute([$texto, $idalquiler]);
}

/**
 * Cambia el estado de una habitación.
 */
function cambiarEstadoHabitacion($conexion, $idhabitacion, $estado) {
    $stmt = $conexion->prepare("UPDATE habitaciones SET estado = ? WHERE idhabitacion = ?");
    $stmt->execute([$estado, $idhabitacion]);
}

/**
 * Obtiene los detalles de un alquiler por su ID.
 */
function obtenerDetalleAlquilerPorId($conexion, $idalquiler) {
    $sql = "SELECT
                a.*, h.numero AS habitacion_numero, h.piso AS habitacion_piso, h.numcamas AS habitacion_numcamas,
                h.precioregular AS habitacion_precio, h.estado AS habitacion_estado, th.tipohabitacion AS habitacion_tipo,
                p.nombres AS cliente_nombres, p.apellidos AS cliente_apellidos, p.numerodoc AS cliente_numerodoc, p.telefono AS cliente_telefono
            FROM alquileres a
            JOIN habitaciones h ON a.idhabitacion = h.idhabitacion
            JOIN tipohabitaciones th ON h.idtipohabitacion = th.idtipohabitacion
            JOIN clientes c ON a.idcliente = c.idcliente
            JOIN personas p ON c.idpersona = p.idpersona
            WHERE a.idalquiler = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$idalquiler]);
    $alquiler = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($alquiler) {
        // Obtener acompañantes
        $sqlA = "SELECT per.nombres, per.apellidos, per.numerodoc
                 FROM huespedes h
                 INNER JOIN personas per ON h.idpersona = per.idpersona
                 WHERE h.idalquiler = ?";
        $stmtA = $conexion->prepare($sqlA);
        $stmtA->execute([$idalquiler]);
        $alquiler['acompanantes'] = $stmtA->fetchAll(PDO::FETCH_ASSOC);
    }

    return $alquiler;
}

/**
 * Obtiene los detalles del alquiler más reciente para una habitación.
 */
function obtenerDetalleAlquilerPorHabitacion($conexion, $idhabitacion) {
    $sql = "SELECT
                a.*, h.numero AS habitacion_numero, h.piso AS habitacion_piso, h.numcamas AS habitacion_numcamas,
                h.precioregular AS habitacion_precio, h.estado AS habitacion_estado, th.tipohabitacion AS habitacion_tipo,
                p.nombres AS cliente_nombres, p.apellidos AS cliente_apellidos, p.numerodoc AS cliente_numerodoc, p.telefono AS cliente_telefono
            FROM alquileres a
            JOIN habitaciones h ON a.idhabitacion = h.idhabitacion
            JOIN tipohabitaciones th ON h.idtipohabitacion = th.idtipohabitacion
            JOIN clientes c ON a.idcliente = c.idcliente
            JOIN personas p ON c.idpersona = p.idpersona
            WHERE a.idhabitacion = ?
            ORDER BY a.fechahorainicio DESC
            LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$idhabitacion]);
    $alquiler = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($alquiler) {
        // Obtener acompañantes
        $sqlA = "SELECT per.nombres, per.apellidos, per.numerodoc
                 FROM huespedes h
                 INNER JOIN personas per ON h.idpersona = per.idpersona
                 WHERE h.idalquiler = ?";
        $stmtA = $conexion->prepare($sqlA);
        $stmtA->execute([$alquiler['idalquiler']]);
        $alquiler['acompanantes'] = $stmtA->fetchAll(PDO::FETCH_ASSOC);
    }

    return $alquiler;
}