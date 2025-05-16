<?php
// Incluir el archivo de conexión
require_once __DIR__ . '/../../config/Conexion.php';

// Verificar que se haya pasado el idhabitacion
if (isset($_GET['idhabitacion'])) {
    $idhabitacion = (int)$_GET['idhabitacion'];  // Aseguramos que sea un valor entero
} else {
    die("No se especificó un ID de habitación.");
}

// Usar la clase Conexion para obtener la conexión
$conexion = Conexion::getConexion();

// Consultar la base de datos para obtener los detalles de la habitación
$sql = "SELECT idhabitacion, numero, precioregular, precioempresarial, precioferiado, estado 
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
// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $fechainicio = $_POST['fechainicio'];
    $fechafin = $_POST['fechafin'];
    $observaciones = $_POST['observaciones'];
    $modalidadpago = $_POST['modalidadpago'];
    $descuento = $_POST['descuento'];
    $total = $_POST['total'];  // El total calculado en el formulario
    $lugarprocedencia = $_POST['lugarprocedencia'];  // Nuevo campo Lugar de procedencia

    // Asegurarse de que haya un cliente seleccionado
    $idcliente = $_POST['idcliente'];  // Este campo lo deberías obtener de alguna forma, como buscar por DNI o seleccionando un cliente

    if (!$idcliente) {
        echo "Debe seleccionar un cliente para proceder.";
        die();
    }

    // Comenzamos la transacción
    $conexion->beginTransaction();
    try {
        // Insertar el alquiler en la base de datos
        $sql = "INSERT INTO alquileres (idcliente, idhabitacion, fechahorainicio, fechahorafin, valoralquiler, modalidadpago, observaciones, lugarprocedencia)
                VALUES (:idcliente, :idhabitacion, :fechainicio, :fechafin, :total, :modalidadpago, :observaciones, :lugarprocedencia)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idcliente', $idcliente, PDO::PARAM_INT);
        $stmt->bindParam(':idhabitacion', $idhabitacion, PDO::PARAM_INT);
        $stmt->bindParam(':fechainicio', $fechainicio);
        $stmt->bindParam(':fechafin', $fechafin);
        $stmt->bindParam(':total', $total, PDO::PARAM_STR);
        $stmt->bindParam(':modalidadpago', $modalidadpago);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':lugarprocedencia', $lugarprocedencia);  // Vinculamos el nuevo campo

        // Ejecutar la consulta para registrar el alquiler
        $stmt->execute();

        // Confirmar la transacción
        $conexion->commit();

        echo "El alquiler se registró correctamente.";
    } catch (Exception $e) {
        // Si ocurre un error, deshacer la transacción
        $conexion->rollBack();
        echo "Error al registrar el alquiler: " . $e->getMessage();
    }
}

?>
