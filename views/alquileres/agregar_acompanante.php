<?php
require_once(__DIR__ . '/../../app/config/Conexion.php');
$conexion = Conexion::getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acompanante_json']) && isset($_GET['idalquiler'])) {
    $idalquiler = intval($_GET['idalquiler']);
    $data = json_decode($_POST['acompanante_json'], true);

    if (!$data || !isset($data['idpersona'])) {
        header("Location: detalle.php?idalquiler=$idalquiler&error=Datos incompletos");
        exit;
    }

    // Evitar duplicados
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM huespedes WHERE idalquiler = ? AND idpersona = ?");
    $stmt->execute([$idalquiler, $data['idpersona']]);
    if ($stmt->fetchColumn() > 0) {
        header("Location: detalle.php?idalquiler=$idalquiler&error=Ya existe ese acompañante");
        exit;
    }

    $tipohuesped = $data['tipohuesped'] ?? 'Adulto';
    $observaciones = $data['observaciones'] ?? '';
    $parentesco = $data['parentesco_tipo'] ?? null;
    $idresponsable = $data['parentesco_responsable'] ?? null;
    $cartapoder = null;

    // Procesar archivo de carta poder si corresponde
    if ($tipohuesped === 'Menor de edad' && $parentesco === 'indirecto' && isset($_FILES['cartapoder_acompanante_detalle'])) {
        $file = $_FILES['cartapoder_acompanante_detalle'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            $carpetaDestino = __DIR__ . '/../../public/img/cartapoder/';
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $nombreArchivo = date('Ymd_His') . '_alquiler' . $idalquiler . '_persona' . $data['idpersona'] . '.' . $extension;
            $rutaDestino = $carpetaDestino . $nombreArchivo;
            if (move_uploaded_file($file['tmp_name'], $rutaDestino)) {
                $cartapoder = $nombreArchivo;
            }
        }
    }

    // Insertar acompañante
    $stmt = $conexion->prepare("INSERT INTO huespedes (idalquiler, idpersona, tipohuesped, observaciones, parentesco, idresponsable, cartapoder) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $idalquiler,
        $data['idpersona'],
        $tipohuesped,
        $observaciones,
        $parentesco,
        $idresponsable,
        $cartapoder
    ]);

    header("Location: detalle.php?idalquiler=$idalquiler&ok=Acompañante agregado");
    exit;
} else {
    header("Location: detalle.php?error=Acceso incorrecto");
    exit;
}
?>