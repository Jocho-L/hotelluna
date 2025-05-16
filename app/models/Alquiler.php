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


?>