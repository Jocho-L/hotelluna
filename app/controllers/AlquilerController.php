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