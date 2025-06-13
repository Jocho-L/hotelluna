<?php
// Incluir el archivo de conexión
require_once(__DIR__ . '/../../app/config/Conexion.php');
$conexion = Conexion::getConexion();

// Si es GET o POST sin acción checkout, obtenemos y consultamos la habitación
if (
    $_SERVER['REQUEST_METHOD'] !== 'POST' ||
    (isset($_POST['accion']) && $_POST['accion'] !== 'checkout')
) {
    if (!isset($_GET['idhabitacion']) && !isset($_POST['idhabitacion'])) {
        die("No se especificó un ID de habitación.");
    }
    $idhabitacion = isset($_GET['idhabitacion']) ? (int)$_GET['idhabitacion'] : (int)$_POST['idhabitacion'];

    // Consultar la base de datos para obtener los detalles de la habitación
    $sql = "SELECT idhabitacion, numero, precioregular, estado
            FROM habitaciones WHERE idhabitacion = :idhabitacion";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':idhabitacion', $idhabitacion, PDO::PARAM_INT);
    $stmt->execute();
    $habitacion = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$habitacion) {
        echo "No se encontró la habitación con el ID: " . htmlspecialchars($idhabitacion);
        die();
    }

    // Aquí puedes seguir usando los datos de la habitación
    $numero_habitacion = $habitacion['numero'];
    $precioregular = $habitacion['precioregular'];
    $estado = $habitacion['estado'];
    $idhabitacion = $habitacion['idhabitacion'];
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && (!isset($_POST['accion']) || $_POST['accion'] !== 'checkout')) {
    $idhabitacion = isset($_POST['idhabitacion']) ? (int)$_POST['idhabitacion'] : null;
    if (!$idhabitacion) {
        die("No se especificó un ID de habitación.");
    }
    // Recoger los datos del formulario
    $fechainicio = $_POST['fechainicio'];
    $fechafin = $_POST['fechafin'];
    $observaciones = $_POST['observaciones'];
    $modalidadpago = $_POST['modalidadpago'];
    $total = $_POST['total'];
    $lugarprocedencia = $_POST['lugarprocedencia'];
    $incluyedesayuno = isset($_POST['incluyedesayuno']) ? 1 : 0;
    $idcliente = $_POST['idcliente'];
    $idmediopago = isset($_POST['idmediopago']) ? $_POST['idmediopago'] : null; // <-- Nuevo
    $placa = isset($_POST['placa']) ? trim($_POST['placa']) : null;

    session_start();
    if (!isset($_SESSION['idusuario'])) {
        echo "Debe iniciar sesión para registrar un alquiler.";
        die();
    }
    $idusuarioentrada = $_SESSION['idusuario'];

    if (!$idcliente) {
        echo "Debe seleccionar un cliente para proceder.";
        die();
    }

    // Verificar si la persona ya es cliente
    $stmt = $conexion->prepare("SELECT idcliente FROM clientes WHERE idpersona = ?");
    $stmt->execute([$idcliente]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        $idcliente_db = $cliente['idcliente'];
    } else {
        // Insertar como cliente
        $stmt = $conexion->prepare("INSERT INTO clientes (idpersona) VALUES (?)");
        $stmt->execute([$idcliente]);
        $idcliente_db = $conexion->lastInsertId();
    }

    $conexion->beginTransaction();
    try {
        // Registrar el alquiler
        $sql = "INSERT INTO alquileres
            (idcliente, idhabitacion, idusuarioentrada, fechahorainicio, fechahorafin, valoralquiler, modalidadpago, idmediopago, observaciones, lugarprocedencia, incluyedesayuno, placa)
            VALUES
            (:idcliente, :idhabitacion, :idusuarioentrada, :fechainicio, :fechafin, :total, :modalidadpago, :idmediopago, :observaciones, :lugarprocedencia, :incluyedesayuno, :placa)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idcliente', $idcliente_db, PDO::PARAM_INT);
        $stmt->bindParam(':idhabitacion', $idhabitacion, PDO::PARAM_INT);
        $stmt->bindParam(':idusuarioentrada', $idusuarioentrada, PDO::PARAM_INT);
        $stmt->bindParam(':fechainicio', $fechainicio);
        $stmt->bindParam(':fechafin', $fechafin);
        $stmt->bindParam(':total', $total, PDO::PARAM_STR);
        $stmt->bindParam(':modalidadpago', $modalidadpago);
        $stmt->bindParam(':idmediopago', $idmediopago, PDO::PARAM_INT);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':lugarprocedencia', $lugarprocedencia);
        $stmt->bindParam(':incluyedesayuno', $incluyedesayuno, PDO::PARAM_BOOL);
        $stmt->bindParam(':placa', $placa);
        $stmt->execute();

        // Obtener el ID del alquiler registrado ANTES de cualquier otro insert
        $idalquiler = $conexion->lastInsertId();

        // Registrar al cliente como huésped tipo "cliente"
        $stmt = $conexion->prepare("INSERT INTO huespedes (idalquiler, idpersona, tipohuesped) VALUES (?, ?, 'cliente')");
        $stmt->execute([$idalquiler, $idcliente]);

        // Registrar acompañantes con sus datos completos
        $acompanantes = $_POST['acompanante'] ?? [];
        $tipos = $_POST['tipohuesped_acompanante'] ?? [];
        $parentesco_tipos = $_POST['parentesco_tipo_acompanante'] ?? [];
        $parentescos = $_POST['parentesco_acompanante'] ?? [];
        $observs = $_POST['observaciones_acompanante'] ?? [];
        // Si hay archivos de carta poder
        $cartapoder_files = $_FILES['cartapoder_acompanante'] ?? null;

        foreach ($acompanantes as $i => $idpersona) {
            $tipo = $tipos[$i] ?? 'Adulto';
            $obs = $observs[$i] ?? '';
            $parentesco_tipo = $parentesco_tipos[$i] ?? null;
            $parentesco = null;
            $idresponsable = null;
            $cartapoder = null;

            if ($tipo === 'Menor de edad') {
                $parentesco = $parentesco_tipo;
                $idresponsable = $parentescos[$i] ?? null;

                // Procesar carta poder si es familiar indirecto
                if (
                    $parentesco === 'indirecto' &&
                    isset($_FILES['cartapoder_acompanante']) &&
                    isset($_FILES['cartapoder_acompanante']['tmp_name'][$i]) &&
                    $_FILES['cartapoder_acompanante']['tmp_name'][$i]
                ) {
                    // Carpeta destino
                    $carpetaDestino = __DIR__ . '/../../public/img/cartapoder/';
                    if (!is_dir($carpetaDestino)) {
                        mkdir($carpetaDestino, 0777, true);
                    }
                    // Nombre: fecha-idalquiler-idpersona.extension
                    $fecha = date('Ymd_His');
                    $extension = pathinfo($_FILES['cartapoder_acompanante']['name'][$i], PATHINFO_EXTENSION);
                    $nombreArchivo = $fecha . '_alquiler' . $idalquiler . '_persona' . $idpersona . '.' . $extension;
                    $rutaDestino = $carpetaDestino . $nombreArchivo;

                    if (move_uploaded_file($_FILES['cartapoder_acompanante']['tmp_name'][$i], $rutaDestino)) {
                        $cartapoder = $nombreArchivo; // Solo el nombre, no la ruta completa
                    } else {
                        $cartapoder = null;
                    }
                }
            }

            $stmt = $conexion->prepare("INSERT INTO huespedes (idalquiler, idpersona, tipohuesped, observaciones, parentesco, idresponsable, cartapoder) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $idalquiler,
                $idpersona,
                $tipo,
                $obs,
                $parentesco,
                $idresponsable,
                $cartapoder
            ]);
        }

        // Cambiar el estado de la habitación a 'ocupada'
        $sqlUpdate = "UPDATE habitaciones SET estado = 'ocupada' WHERE idhabitacion = :idhabitacion";
        $stmtUpdate = $conexion->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':idhabitacion', $idhabitacion, PDO::PARAM_INT);
        $stmtUpdate->execute();

        $conexion->commit();
        echo "El alquiler se registró correctamente, la habitación fue marcada como ocupada, y el cliente fue registrado como huésped.";
        header("Location: /hotelluna/views/index.php?mensaje=Cliente registrado exitosamente.");
        exit;
    } catch (Exception $e) {
        $conexion->rollBack();
        echo "Error al registrar el alquiler: " . $e->getMessage();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'checkout') {
    session_start();
    if (!isset($_SESSION['idusuario'])) {
        echo "Debe iniciar sesión para hacer check-out.";
        die();
    }
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
        $sql = "UPDATE habitaciones SET estado = 'disponible' WHERE idhabitacion = ?"; //cambiar a 'disponible' o 'mantenimiento' según tu lógica
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
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Registrar cliente principal como huésped adulto
    $idalquiler = $_POST['idalquiler'];
    $idcliente = $_POST['idcliente'];
    $observaciones = ''; // Puedes obtenerlo si lo necesitas
    $tipohuesped = 'Adulto';
    $parentesco = null;
    $idresponsable = null;
    $cartapoder = null;

    // Insertar cliente principal
    $stmt = $conexion->prepare("INSERT INTO huespedes (idalquiler, idpersona, observaciones, tipohuesped, parentesco, idresponsable, cartapoder) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$idalquiler, $idcliente, $observaciones, $tipohuesped, $parentesco, $idresponsable, $cartapoder]);

    // Registrar acompañantes
    $acompanantes = $_POST['acompanante'] ?? [];
    $tipos = $_POST['tipohuesped_acompanante'] ?? [];
    $parentescos = $_POST['parentesco_acompanante'] ?? [];
    $observs = $_POST['observaciones_acompanante'] ?? [];

    foreach ($acompanantes as $i => $idpersona) {
        $tipo = $tipos[$i];
        $obs = $observs[$i] ?? '';
        if ($tipo === 'Menor de edad') {
            // parentesco es el id del responsable
            $idresponsable = $parentescos[$i];
            $parentesco = null;
        } else {
            $parentesco = $parentescos[$i];
            $idresponsable = null;
        }
        $stmt->execute([$idalquiler, $idpersona, $obs, $tipo, $parentesco, $idresponsable, null]);
    }
}
