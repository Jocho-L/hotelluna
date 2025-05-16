<?php
require_once(__DIR__ . '/../config/Conexion.php');
$conexion = Conexion::getConexion();

require_once(__DIR__ . '/../models/Alquiler.php');

$idhabitacion = isset($_GET['idhabitacion']) ? intval($_GET['idhabitacion']) : 0;
$habitacion = ($idhabitacion > 0) ? obtenerHabitacionPorId($conexion, $idhabitacion) : null;


?>
