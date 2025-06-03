<?php
function obtenerHabitacionPorId($conexion, $idhabitacion) {
    $sql = "SELECT h.idhabitacion, h.numero, h.piso, h.numcamas, h.precioregular, h.estado, th.tipohabitacion
            FROM habitaciones h
            JOIN tipohabitaciones th ON h.idtipohabitacion = th.idtipohabitacion
            WHERE h.idhabitacion = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([$idhabitacion]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function obtenerDetalleAlquilerPorHabitacion($conexion, $idhabitacion) {
    // Obtener el alquiler activo de la habitación
    $sql = "SELECT
            a.idalquiler,
            a.fechahorainicio,
            a.fechahorafin,
            a.valoralquiler,
            a.modalidadpago,
            a.lugarprocedencia,
            a.observaciones,
            a.incluyedesayuno,
            h.idhabitacion,
            h.numero AS habitacion_numero,
            h.piso AS habitacion_piso,
            h.numcamas AS habitacion_numcamas,
            h.precioregular AS habitacion_precio,
            h.estado AS habitacion_estado,
            th.tipohabitacion AS habitacion_tipo,
            c.idcliente,
            p.nombres AS cliente_nombres,
            p.apellidos AS cliente_apellidos,
            p.numerodoc AS cliente_numerodoc,
            p.telefono AS cliente_telefono
        FROM alquileres a
        JOIN habitaciones h ON a.idhabitacion = h.idhabitacion
        JOIN tipohabitaciones th ON h.idtipohabitacion = th.idtipohabitacion
        JOIN clientes c ON a.idcliente = c.idcliente
        JOIN personas p ON c.idpersona = p.idpersona
        WHERE a.idhabitacion = ?
        ORDER BY a.idalquiler DESC
        LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$idhabitacion]);
    $alquiler = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($alquiler) {
        // Obtener acompañantes
        $sqlA = "SELECT per.nombres, per.apellidos, per.numerodoc
                 FROM huespedes h
                 JOIN personas per ON h.idpersona = per.idpersona
                 WHERE h.idalquiler = ? AND h.tipohuesped = 'acompañante'";
        $stmtA = $conexion->prepare($sqlA);
        $stmtA->execute([$alquiler['idalquiler']]);
        $alquiler['acompanantes'] = $stmtA->fetchAll(PDO::FETCH_ASSOC);
    }

    return $alquiler;
}

?>