<?php
// Incluir el archivo de conexión
require_once(__DIR__ . '/../../app/config/Conexion.php');

// Verificar que se haya pasado el idhabitacion
if (isset($_GET['idhabitacion'])) {
    $idhabitacion = (int)$_GET['idhabitacion'];  // Aseguramos que sea un valor entero
} else {
    die("No se especificó un ID de habitación.");
}

// Usar la clase Conexion para obtener la conexión
$conexion = Conexion::getConexion();

// Consultar la base de datos para obtener los detalles de la habitación
$sql = "SELECT idhabitacion, numero, precioregular, estado 
        FROM habitaciones WHERE idhabitacion = :idhabitacion";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':idhabitacion', $idhabitacion, PDO::PARAM_INT);

// Ejecutar la consulta
$stmt->execute();
$habitacion = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si la habitación existe
if (!$habitacion) {
    echo "No se encontró la habitación con el ID: " . htmlspecialchars($idhabitacion);
    die();  // Detenemos la ejecución si no se encuentra la habitación
}

// Obtener los valores de la habitación
$numero_habitacion = $habitacion['numero'];
$precioregular = $habitacion['precioregular'];
$estado = $habitacion['estado'];
$idhabitacion = $habitacion['idhabitacion'];

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            (idcliente, idhabitacion, idusuarioentrada, fechahorainicio, fechahorafin, valoralquiler, modalidadpago, idmediopago, observaciones, lugarprocedencia, incluyedesayuno)
            VALUES 
            (:idcliente, :idhabitacion, :idusuarioentrada, :fechainicio, :fechafin, :total, :modalidadpago, :idmediopago, :observaciones, :lugarprocedencia, :incluyedesayuno)";
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
        $stmt->execute();

        // Obtener el ID del alquiler registrado ANTES de cualquier otro insert
        $idalquiler = $conexion->lastInsertId();

        // Registrar al cliente como huésped tipo "cliente"
        $stmt = $conexion->prepare("INSERT INTO huespedes (idalquiler, idpersona, tipohuesped) VALUES (?, ?, 'cliente')");
        $stmt->execute([$idalquiler, $idcliente]);

        // Registrar acompañantes como huéspedes tipo "acompañante"
        if (!empty($_POST['acompanantes_json'])) {
            $acompanantes = json_decode($_POST['acompanantes_json'], true);
            foreach ($acompanantes as $idpersonaAcompanante) {
                // Evita registrar al cliente principal como acompañante
                if ($idpersonaAcompanante == $idcliente) continue;
                $stmt = $conexion->prepare("INSERT INTO huespedes (idalquiler, idpersona, tipohuesped, observaciones) VALUES (?, ?, 'acompañante', 'acompañante')");
                $stmt->execute([$idalquiler, $idpersonaAcompanante]);
            }
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
}
