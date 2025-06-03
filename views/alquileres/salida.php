<?php
require_once(__DIR__ . '/../../app/config/Conexion.php');
$conexion = Conexion::getConexion();

session_start();
if (!isset($_SESSION['idusuario'])) {
    echo "Debe iniciar sesión para hacer check-out.";
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idalquiler'])) {
    $idalquiler = intval($_POST['idalquiler']);
    $idusuariosalida = $_SESSION['idusuario'];

    // Obtener la habitación asociada al alquiler
    $sql = "SELECT idhabitacion FROM alquileres WHERE idalquiler = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$idalquiler]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $idhabitacion = $row ? $row['idhabitacion'] : null;

    if ($idhabitacion) {
        // Cambiar estado de la habitación a 'mantenimiento'
        $sql = "UPDATE habitaciones SET estado = 'mantenimiento' WHERE idhabitacion = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$idhabitacion]);

        // Actualizar idusuariosalida y fechahorafin en alquileres
        $sql = "UPDATE alquileres SET idusuariosalida = ?, fechahorafin = NOW() WHERE idalquiler = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$idusuariosalida, $idalquiler]);

        header("Location: detalle.php?idhabitacion=$idhabitacion");
        exit;
    } else {
        header("Location: index.php?error=No se encontró la habitación");
        exit;
    }
} else {
    header("Location: index.php?error=Solicitud inválida");
    exit;
}